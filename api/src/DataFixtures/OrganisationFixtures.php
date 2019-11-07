<?php


namespace App\DataFixtures;

use App\Entity\Member;
use App\Entity\Role;
use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\Organisation;
use App\Entity\Address;

class OrganisationFixtures extends Fixture
{
    public function loadOrganisations(ObjectManager $manager):array
    {
        //create organisations
        $organisationsDataset = array();
        $organisations = array();

        foreach($organisationsDataset as $k=>$datapoint){
            $organisation = new Organisation();
            $organisation->setName($datapoint->name);
            $organisation->setOrganisationNumber($datapoint->orgNo);
            $organisation->setGoal($datapoint->goal);

            $address = $this->loadAddress($datapoint);

            $manager->persist($address);

            $organisation->setLocation($address);

            $manager->persist($organisation);
            array_push($organisations, $organisation);

            if(($k % 25) == 0 ){
                $manager->flush();
                $manager->clear();
            }

        }
        $manager->flush();
        $manager->clear();
        return $organisations;
    }
    public function loadRoles(ObjectManager $manager):array
    {
        $rolesDataset = array();
        $roles = array();

        foreach($rolesDataset as $k=>$datapoint){
            $role = new Role();
            $role->setName($datapoint->name);
            $role->setRequiresDifferentRole($datapoint->requiresDifferentRole);
            $role->setCanViewOtherMembers($datapoint->canViewOtherMembers);
            $role->setCanEditOtherMembers($datapoint->canEditOtherMembers);
            $role->setCanEditContributionStatus($datapoint->canEditContributionStatus);
            $role->setOrganisations($datapoint->organisations);

            array_push($roles, $role);
            $manager->persist($role);
            if(($k % 25) == 0 ){
                $manager->flush();
                $manager->clear();
            }
        }
        $manager->flush();
        $manager->clear();

        return $roles;
    }
    private function setMemberRoles(Member $member, array $roles):Member
    {
        foreach($roles as $role)
        {
            $member->addRole($role);
        }
        return $member;
    }
    private function setMemberOrganisations(Member $member, array $organisations):Member
    {
        foreach($organisations as $organisation)
        {
            $member->addOrganisation($organisation);
        }
        return $member;
    }
    private function setMemberTags(Member $member, array $tags):Member
    {
        foreach($tags as $tag)
        {
            $member->addTag($tag);
        }
        return $member;
    }
    public function loadMembers(ObjectManager $manager):array
    {
        $membersDataset = array();
        $members = array();

        foreach($membersDataset as $k=>$datapoint) {
            $member = new Member();
            $member->setFirstName($datapoint->firstName);
            $member->setLastName($datapoint->lastName);
            $member->setDateOfBirth($datapoint->dateOfBirth);
            $member->setEmail($datapoint->email);
            $member->setUserName($datapoint->username);
            $member->setPassWord($datapoint->password);
            $member->setContributionPaid($datapoint->paid);
            $member->setMembershipEndDate($datapoint->endDate);
            $member = $this->setMemberRoles($member, $datapoint->roles);
//            $member->add($datapoint->roles);
            $member = $this->setMemberOrganisations($member, $datapoint->organisations);
            $member = $this->setMemberTags($member, $datapoint->tags);

            array_push($members, $member);
            $manager->persist($member);
            if (( $k % 25 ) == 0) {
                $manager->flush();
                $manager->clear();
            }
        }
        $manager->flush();
        $manager->clear();

        return $members;

    }
    public function loadTags(ObjectManager $manager):array
    {

        $tagsDataset = array();
        $tags = array();

        foreach($tagsDataset as $k=>$datapoint){
            $tag = new Tag();
            $tag->setName($datapoint->name);
            array_push($tags, $tag);
            $manager->persist($tag);

            if(( $k % 25 ) == 0 )
            {
                $manager->flush();
                $manager->clear();
            }
        }
        $manager->flush();
        $manager->clear();

        return $tags;

    }
    public function loadAddress($datapoint):?Address
    {
        //create the address
        $address = new Address();
        $address->setStreet($datapoint->addressStreet);
        $address->setNumber($datapoint->addressNumber);
        $address->setPostalCode($datapoint->addressPostalCode);
        $address->setSettlement($datapoint->addressSettlement);
        $address->setProvince($datapoint->addressProvince);
        $address->setCountry($datapoint->addressCountry);

        return $address;
    }
    public function load(ObjectManager $manager){


    }

}
