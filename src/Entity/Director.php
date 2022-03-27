<?php

namespace App\Entity;

use App\Repository\DirectorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DirectorRepository::class)]
class Director
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $nombre;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $fechaNacimiento;

    #[ORM\OneToMany(mappedBy: 'director', targetEntity: Pelicula::class)]
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
     * @return \DateTimeInterface|null
     */
    public function getFechaNacimiento(): ?\DateTimeInterface
    {
        return $this->fechaNacimiento;
    }

    /**
     * @param \DateTimeInterface|null $fechaNacimiento
     */
    public function setFechaNacimiento(?\DateTimeInterface $fechaNacimiento): void
    {
        $this->fechaNacimiento = $fechaNacimiento;
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
            $pelicula->setDirector($this);
        }
    }

    public function removePelicula(Pelicula $pelicula): void
    {
        if ($this->peliculas->removeElement($pelicula)) {
            // set the owning side to null (unless already changed)
            if ($pelicula->getDirector() === $this) {
                $pelicula->setDirector(null);
            }
        }
    }
}
