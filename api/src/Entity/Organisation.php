<?php

namespace App\Entity;


use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ApiResource(
 *     normalizationContext={"groups"={"read"}, "enable_max_depth"=true},
 *     denormalizationContext={"groups"={"write"}, "enable_max_depth"=true}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\OrganisationRepository")
 */
class Organisation
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
    private $name;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"read","write"})
     * @Assert\NotBlank
     * @Assert\Length(
     *     min = 8,
     *     max = 8,
     *      minMessage = "Your organisation number must be {{ limit }} characters long",
     *      maxMessage = "Your organisation number must be {{ limit }} characters long"
     *)
     */
    private $organisationNumber;

    /**
     * @ORM\Column(type="text")
     * @Groups({"read","write"})
     * @Assert\NotBlank
     */
    private $goal;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Address", inversedBy="organisations", cascade="persist")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read","write"})
     * @MaxDepth(1)
     * @Assert\NotBlank
     * @Assert\Valid
     */
    private $location;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Member", mappedBy="organisations")
     * @Groups({"read","write"})
     * @MaxDepth(1)
     */
    private $members;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Role", mappedBy="organisations", cascade="persist")
     */
    private $roles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Tag", mappedBy="organisation", orphanRemoval=true)
     */
    private $tags;

    public function __construct()
    {
        $this->members = new ArrayCollection();
        $this->roles = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getOrganisationNumber(): ?int
    {
        return $this->organisationNumber;
    }

    public function setOrganisationNumber(int $organisationNumber): self
    {
        $this->organisationNumber = $organisationNumber;

        return $this;
    }

    public function getGoal(): ?string
    {
        return $this->goal;
    }

    public function setGoal(string $goal): self
    {
        $this->goal = $goal;

        return $this;
    }

    public function getLocation(): ?Address
    {
        return $this->location;
    }

    public function setLocation(?Address $location): self
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return Collection|Member[]
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(Member $member): self
    {
        if (!$this->members->contains($member)) {
            $this->members[] = $member;
            $member->addOrganisation($this);
        }

        return $this;
    }

    public function removeMember(Member $member): self
    {
        if ($this->members->contains($member)) {
            $this->members->removeElement($member);
            $member->removeOrganisation($this);
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
            $role->setOrganisations($this);
        }

        return $this;
    }

    public function removeRole(Role $role): self
    {
        if ($this->roles->contains($role)) {
            $this->roles->removeElement($role);
            // set the owning side to null (unless already changed)
            if ($role->getOrganisations() === $this) {
                $role->setOrganisations(null);
            }
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
            $tag->setOrganisation($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            // set the owning side to null (unless already changed)
            if ($tag->getOrganisation() === $this) {
                $tag->setOrganisation(null);
            }
        }

        return $this;
    }
}
