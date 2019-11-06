<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\RoleRepository")
 */
class Role
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $requiresDifferentRole;

    /**
     * @ORM\Column(type="boolean")
     */
    private $canViewOtherMembers;

    /**
     * @ORM\Column(type="boolean")
     */
    private $canEditOtherMembers;

    /**
     * @ORM\Column(type="boolean")
     */
    private $canEditContributionStatus;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Member", mappedBy="roles1")
     */
    private $members;

    public function __construct()
    {
        $this->members = new ArrayCollection();
    }

    public function getId(): ?int
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
