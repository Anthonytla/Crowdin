<?php

namespace App\Entity;

use App\Repository\TraductionSourceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TraductionSourceRepository::class)
 */
class TraductionSource
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=TraductionTarget::class, mappedBy="source", orphanRemoval=true)
     */
    private $targets;

    /**
     * @ORM\ManyToOne(targetEntity=Project::class, inversedBy="sources")
     * @ORM\JoinColumn(nullable=false)
     */
    private $project;

    /**
     * @ORM\Column(type="text")
     */
    private $target;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $source;

    public function __construct()
    {
        $this->targets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|TraductionTarget[]
     */
    public function getTargets(): Collection
    {
        return $this->targets;
    }

    public function addTarget(TraductionTarget $target): self
    {
        if (!$this->targets->contains($target)) {
            $this->targets[] = $target;
            $target->setSource($this);
        }

        return $this;
    }

    public function removeTarget(TraductionTarget $target): self
    {
        if ($this->targets->removeElement($target)) {
            // set the owning side to null (unless already changed)
            if ($target->getSource() === $this) {
                $target->setSource(null);
            }
        }

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getTarget(): ?string
    {
        //return mb_convert_encoding($this->target, "UTF-8", "latin1");
        return $this->target;
    }

    public function setTarget(string $target): self
    {
        $this->target = mb_convert_encoding($target, "UTF-8", "Windows-1252");
        //$this->target = $target;
        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(string $source): self
    {
        $this->source = $source;

        return $this;
    }
}
