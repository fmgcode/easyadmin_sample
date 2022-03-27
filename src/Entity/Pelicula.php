<?php

namespace App\Entity;

use App\Repository\PeliculaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PeliculaRepository::class)]
class Pelicula
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $titulo;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $fechaPublicacion;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $duracion;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $productora;

    #[ORM\ManyToMany(targetEntity: Genero::class, inversedBy: 'peliculas', cascade: ['persist'])]
    private Collection $generos;

    #[ORM\ManyToMany(targetEntity: Actor::class, inversedBy: 'peliculas', cascade: ['persist'])]
    private Collection $actores;

    #[ORM\ManyToOne(targetEntity: Director::class, inversedBy: 'peliculas', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Director $director;

    public function __construct()
    {
        $this->actores = new ArrayCollection();
        $this->generos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    /**
     * @param string|null $titulo
     */
    public function setTitulo(?string $titulo): void
    {
        $this->titulo = $titulo;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getFechaPublicacion(): ?\DateTimeInterface
    {
        return $this->fechaPublicacion;
    }

    /**
     * @param \DateTimeInterface|null $fechaPublicacion
     */
    public function setFechaPublicacion(?\DateTimeInterface $fechaPublicacion): void
    {
        $this->fechaPublicacion = $fechaPublicacion;
    }

    /**
     * @return int|null
     */
    public function getDuracion(): ?int
    {
        return $this->duracion;
    }

    /**
     * @param int|null $duracion
     */
    public function setDuracion(?int $duracion): void
    {
        $this->duracion = $duracion;
    }

    /**
     * @return string|null
     */
    public function getProductora(): ?string
    {
        return $this->productora;
    }

    /**
     * @param string|null $productora
     */
    public function setProductora(?string $productora): void
    {
        $this->productora = $productora;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getGeneros(): ArrayCollection|Collection
    {
        return $this->generos;
    }

    /**
     * @param ArrayCollection|Collection $generos
     */
    public function setGeneros(ArrayCollection|Collection $generos): void
    {
        $this->generos = $generos;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getActores(): ArrayCollection|Collection
    {
        return $this->actores;
    }

    /**
     * @param ArrayCollection|Collection $actores
     */
    public function setActores(ArrayCollection|Collection $actores): void
    {
        $this->actores = $actores;
    }

    /**
     * @return Director|null
     */
    public function getDirector(): ?Director
    {
        return $this->director;
    }

    /**
     * @param Director|null $director
     */
    public function setDirector(?Director $director): void
    {
        $this->director = $director;
    }

    public function addGenero(Genero $genero): void
    {
        if (!$this->generos->contains($genero)) {
            $this->generos->add($genero);
        }
    }

    public function removeGenero(Genero $genero): void
    {
        $this->generos->removeElement($genero);
    }

    public function addActor(Actor $actor): void
    {
        if (!$this->actores->contains($actor)) {
            $this->actores->add($actor);
        }
    }

    public function removeActor(Actor $actor): void
    {
        $this->actores->removeElement($actor);
    }
}
