<?php

namespace App\Entity;

use App\Repository\MufasRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MufasRepository::class)]
class Mufas
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $zonal = null;

    #[ORM\Column(length: 50)]
    private ?string $sitio = null;

    #[ORM\Column(length: 100)]
    private ?string $codigo_mufa = null;

    #[ORM\Column]
    private ?float $distancia_optica = null;

    #[ORM\Column(length: 255)]
    private ?float $latitud = null;

    #[ORM\Column(length: 255)]
    private ?float $longitud = null;

    #[ORM\Column(length: 255)]
    private ?string $referencia = null;

    #[ORM\Column(length: 255)]
    private ?string $cable = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getZonal(): ?string
    {
        return $this->zonal;
    }

    public function setZonal(string $zonal): static
    {
        $this->zonal = $zonal;

        return $this;
    }

    public function getSitio(): ?string
    {
        return $this->sitio;
    }

    public function setSitio(string $sitio): static
    {
        $this->sitio = $sitio;

        return $this;
    }

    public function getCodigoMufa(): ?string
    {
        return $this->codigo_mufa;
    }

    public function setCodigoMufa(string $codigo_mufa): static
    {
        $this->codigo_mufa = $codigo_mufa;

        return $this;
    }

    public function getDistanciaOptica(): ?float
    {
        return $this->distancia_optica;
    }

    public function setDistanciaOptica(float $distancia_optica): static
    {
        $this->distancia_optica = $distancia_optica;

        return $this;
    }

    public function getLatitud(): ?float
    {
        return $this->latitud;
    }

    public function setLatitud(float $latitud): static
    {
        $this->latitud = $latitud;

        return $this;
    }

    public function getLongitud(): ?float
    {
        return $this->longitud;
    }

    public function setLongitud(float $longitud): static
    {
        $this->longitud = $longitud;

        return $this;
    }

    public function getReferencia(): ?string
    {
        return $this->referencia;
    }

    public function setReferencia(string $referencia): static
    {
        $this->referencia = $referencia;

        return $this;
    }

    public function getCable(): ?string
    {
        return $this->cable;
    }

    public function setCable(string $cable): static
    {
        $this->cable = $cable;

        return $this;
    }
}
