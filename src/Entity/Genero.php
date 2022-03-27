<?php

namespace App\Entity;

use App\Repository\GeneroRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: GeneroRepository::class)]
#[UniqueEntity('nombre')]
class Genero
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private ?string $nombre;

    #[ORM\ManyToMany(targetEntity: Pelicula::class, mappedBy: 'generos')]
    private Collection $peliculas;

    public function __construct()
    {
        $this->peliculas = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getNombre();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    /**
     * @param string|null $nombre
     */
    public function setNombre(?string $nombre): void
    {
        $this->nombre = $nombre;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getPeliculas(): ArrayCollection|Collection
    {
        return $this->peliculas;
    }

    /**
     * @param ArrayCollection|Collection $peliculas
     */
    public function setPeliculas(ArrayCollection|Collection $peliculas): void
    {
        $this->peliculas = $peliculas;
    }

    public function addPelicula(Pelicula $pelicula): void
    {
        if (!$this->peliculas->contains($pelicula)) {
            $this->peliculas->add($pelicula);
            $pelicula->addGenero($this);
        }
    }

    public function removePelicula(Pelicula $pelicula): void
    {
        if ($this->peliculas->removeElement($pelicula)) {
            $pelicula->removeGenero($this);
        }
    }
}
