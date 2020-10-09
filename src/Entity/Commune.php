<?php

namespace App\Entity;

use App\Repository\CommuneRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommuneRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Commune
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string")
     */
    private $code;

    /**
     * @ORM\Column(type="string")
     */
    private $codeDepartement;

    /**
     * @ORM\Column(type="string")
     */
    private $codeRegion;

    /**
     * @ORM\Column(type="array")
     */
    private $codesPostaux = [];

    /**
     * @ORM\Column(type="integer")
     */
    private $population;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getCodeDepartement(): ?string
    {
        return $this->codeDepartement;
    }

    public function setCodeDepartement(string $codeDepartement): self
    {
        $this->codeDepartement = $codeDepartement;

        return $this;
    }

    public function getCodeRegion(): ?string
    {
        return $this->codeRegion;
    }

    public function setCodeRegion(string $codeRegion): self
    {
        $this->codeRegion = $codeRegion;

        return $this;
    }

    public function getCodesPostaux(): ?array
    {
        return $this->codesPostaux;
    }

    public function setCodesPostaux(array $codesPostaux): self
    {
        $this->codesPostaux = $codesPostaux;

        return $this;
    }

    public function getPopulation(): ?int
    {
        return $this->population;
    }

    public function setPopulation(int $population): self
    {
        $this->population = $population;

        return $this;
    }
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function setSlugValue(){
        $nom = $this->getNom();
        $slugify = new Slugify();
        $this->slug = $slugify->slugify($nom,'-');
        $this->slug='tot';
    }
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
