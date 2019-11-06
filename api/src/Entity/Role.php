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

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"read"}, "enable_max_depth"=true},
 *     denormalizationContext={"groups"={"write"}, "enable_max_depth"=true}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\RoleRepository")
 */
class Role
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
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read","write"})
     * @Assert\NotBlank
     */
    private $requiresDifferentRole;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"read","write"})
     * @Assert\NotBlank
     */
    private $canViewOtherMembers;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"read","write"})
     * @Assert\NotBlank
     */
    private $canEditOtherMembers;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"read","write"})
     * @Assert\NotBlank
     */
    private $canEditContributionStatus;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Member", mappedBy="roles1", cascade="persist")
     * @Groups({"read","write"})
     * @MaxDepth(1)
     * @Assert\NotBlank
     */
    private $members;

    public function __construct()
    {
        $this->members = new ArrayCollection();
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

    public function getRequiresDifferentRole(): ?string
    {
        return $this->requiresDifferentRole;
    }

    public function setRequiresDifferentRole(?string $requiresDifferentRole): self
    {
        $this->requiresDifferentRole = $requiresDifferentRole;

        return $this;
    }

    public function getCanViewOtherMembers(): ?bool
    {
        return $this->canViewOtherMembers;
    }

    public function setCanViewOtherMembers(bool $canViewOtherMembers): self
    {
        $this->canViewOtherMembers = $canViewOtherMembers;

        return $this;
    }

    public function getCanEditOtherMembers(): ?bool
    {
        return $this->canEditOtherMembers;
    }

    public function setCanEditOtherMembers(bool $canEditOtherMembers): self
    {
        $this->canEditOtherMembers = $canEditOtherMembers;

        return $this;
    }

    public function getCanEditContributionStatus(): ?bool
    {
        return $this->canEditContributionStatus;
    }

    public function setCanEditContributionStatus(bool $canEditContributionStatus): self
    {
        $this->canEditContributionStatus = $canEditContributionStatus;

        return $this;
    }


    /**
     * @return Collection|Member[]
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMembers(Member $members): self
    {
        if (!$this->members->contains($members)) {
            $this->members[] = $members;
            $members->addRoles($this);
        }

        return $this;
    }

    public function removeMembers(Member $members): self
    {
        if ($this->members->contains($members)) {
            $this->members->removeElement($members);
            $members->removeRoles($this);
        }

        return $this;
    }
}
