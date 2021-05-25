<?php

namespace App\Entity;

use App\Repository\TraductionTargetRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TraductionTargetRepository::class)
 */
class TraductionTarget
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=TraductionSource::class, inversedBy="targets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $source;

    /**
     * @ORM\ManyToOne(targetEntity=Lang::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $lang;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $target;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSource(): ?TraductionSource
    {
        return $this->source;
    }

    public function setSource(?TraductionSource $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getLang(): ?Lang
    {
        return $this->lang;
    }

    public function setLang(?Lang $lang): self
    {
        $this->lang = $lang;

        return $this;
    }

    public function getTarget(): ?string
    {
        return $this->target;
    }

    public function setTarget(?string $target): self
    {
        $this->target = $target;

        return $this;
    }
}
