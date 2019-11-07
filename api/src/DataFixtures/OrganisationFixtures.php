<?php


namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\Organisation;
use App\Entity\Address;

class OrganisationFixtures extends Fixture
{
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
        $dataset = array();

        foreach($dataset as $k=>$datapoint){
            $entity = new Organisation();
            $entity->setName($datapoint->name);
            $entity->setOrganisationNumber($datapoint->orgNo);
            $entity->setGoal($datapoint->goal);

            $address = $this->loadAddress($datapoint);

            $manager->persist($address);

            $entity->setLocation($address);

            $manager->persist($entity);

            if(($k % 25) == 0 ){
                $manager->flush();
                $manager->clear();
            }

        }
    }

}
