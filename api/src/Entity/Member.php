<?php

namespace App\Entity;


use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;


/**
 * @ApiResource(
 *     normalizationContext={"groups"={"read"}, "enable_max_depth"=true},
 *     denormalizationContext={"groups"={"write"}, "enable_max_depth"=true}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\MemberRepository")
 */
class Member
{
    /**
     * @var \Ramsey\Uuid\UuidInterface
     *
     * @ApiProperty(
     * 	   identifier=true,
     *     attributes={
     *         "openapi_context"={
     *         	   "description" = "The UUID identifier of this object",
     *             "type"="string",
     *             "format"="uuid",
     *             "example"="e2984465-190a-4562-829e-a8cca81aa35d"
     *         }
     *     }
     * )
     *
     * @Groups({"read"})
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     * @Groups({"read","write"})
     * @Assert\Uuid
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read","write"})
     * @Assert\NotBlank
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read","write"})
     * @Assert\NotBlank
     */
    private $lastName;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"read","write"})
     * @Assert\NotBlank
     * @Assert\DateTime
     */
    private $dateOfBirth;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read","write"})
     * @Assert\NotBlank
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true
     * )
     */
    private $email;

//    /**
//     * @ORM\Column(type="string", length=255)
//     * @Groups({"read","write"})
//     * @Assert\NotBlank
//     */
//    private $userName;
//
//    /**
//     * @ORM\Column(type="string", length=255)
//     * @Groups({"read","write"})
//     * @Assert\NotBlank
//     * SecurityAssert\UserPassword(
//     *     message = "Wrong password"
//     * )
//     */
//    private $passWord;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"read","write"})
     * @Assert\NotBlank
     */
    private $contributionPaid;


    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Organisation", inversedBy="members", cascade="persist")
     * @Groups({"read","write"})
     * @MaxDepth(1)
     * @Assert\NotBlank
     */
    private $organisations;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Role", inversedBy="members", cascade="persist")
     * @Groups({"read","write"})
     * @MaxDepth(1)
     * @Assert\NotBlank
     */
    private $roles;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", mappedBy="members")
     */
    private $tags;

    /**
     * @ORM\Column(type="datetime")
     */
    private $membershipEndDate;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->organisations = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(\DateTimeInterface $dateOfBirth): self
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    public function getPassWord(): ?string
    {
        return $this->passWord;
    }

    public function setPassWord(string $passWord): self
    {
        $this->passWord = $passWord;

        return $this;
    }

    public function getContributionPaid(): ?bool
    {
        return $this->contributionPaid;
    }

    public function setContributionPaid(bool $contributionPaid): self
    {
        $this->contributionPaid = $contributionPaid;

        return $this;
    }

//    /**
//     * @return Collection|Role[]
//     */
//    public function getRoles(): Collection
//    {
//        return $this->roles;
//    }
//
//    public function addRoles(Role $roles): self
//    {
//        if (!$this->roles->contains($roles)) {
//            $this->roles[] = $roles;
//        }
//
//        return $this;
//    }
//
//    public function removeRoles(Role $roles): self
//    {
//        if ($this->roles->contains($roles)) {
//            $this->roles->removeElement($roles);
//        }
//
//        return $this;
//    }

    /**
     * @return Collection|Organisation[]
     */
    public function getOrganisations(): Collection
    {
        return $this->organisations;
    }

    public function addOrganisation(Organisation $organisation): self
    {
        if (!$this->organisations->contains($organisation)) {
            $this->organisations[] = $organisation;
        }

        return $this;
    }

    public function removeOrganisation(Organisation $organisation): self
    {
        if ($this->organisations->contains($organisation)) {
            $this->organisations->removeElement($organisation);
        }

        return $this;
    }

    /**
     * @return Collection|Role[]
     */
    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function addRole(Role $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function removeRole(Role $role): self
    {
        if ($this->roles->contains($role)) {
            $this->roles->removeElement($role);
        }

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addMember($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            $tag->removeMember($this);
        }

        return $this;
    }

    public function getMembershipEndDate(): ?\DateTimeInterface
    {
        return $this->membershipEndDate;
    }

    public function setMembershipEndDate(\DateTimeInterface $membershipEndDate): self
    {
        $this->membershipEndDate = $membershipEndDate;

        return $this;
    }
}
