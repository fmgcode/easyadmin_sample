<?php

namespace App\Command;

use App\Entity\Actor;
use App\Entity\Director;
use App\Entity\Genero;
use App\Entity\Pelicula;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class CargarDatos extends Command
{
    private $csvParsingOptions = [
        'finder_in' => __DIR__.'/',
        'finder_name' => 'IMDb_movies.csv',
        'ignoreFirstLine' => true
    ];

    private EntityManagerInterface $em;

    /**
     * @param EntityManagerInterface $em
     * @param string|null $name
     */
    public function __construct(EntityManagerInterface $em,
                                string $name = null)
    {
        parent::__construct($name);
        $this->em = $em;
    }

    protected function configure()
    {
        $this->setName('command-cargar-datos')
            ->setDescription('')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $ignoreFirstLine = $this->csvParsingOptions['ignoreFirstLine'];

        $finder = new Finder();
        $finder->files()
            ->in($this->csvParsingOptions['finder_in'])
            ->name($this->csvParsingOptions['finder_name'])
        ;
        foreach ($finder as $file) { $csv = $file; }

        $rows = array();
        if (($handle = fopen($csv->getRealPath(), "r")) !== FALSE) {
            $i = 0;

            while (($data = fgetcsv($handle, null, ",")) !== FALSE && $i <= 10) {
                $i++;
                echo $i . PHP_EOL;
                if ($ignoreFirstLine && $i == 1) {
                    continue;
                }
                //title 1
                //date_published 4
                //genre 5
                //duration 6
                //production_company 11
                // director 9
                //actors 12
                $title = $data[1];
                $datePublished = \DateTime::createFromFormat('Y-m-d', $data[4]);
                $genre = explode(',', $data[5]);
                $duration = $data[6];
                $productionCompany = $data[11];
                $director = $data[9];
                $actors = explode(',', $data[12]);

                $director = $this->createOrUpdateDirector($director);
                $generos = $this->createOrUpdateGenre($genre);
                $actores = $this->createOrUpdateActor($actors);

                $pelicula = $this->createOrUpdateFilm(
                    $title,
                    $datePublished,
                    $duration,
                    $productionCompany,
                    $generos,
                    $actores,
                    $director
                );

                $this->em->persist($pelicula);
            }
            fclose($handle);
        }

        $this->em->flush();

        return 0;
    }

    private function createOrUpdateGenre(array $nombres): Collection
    {
        $generos = new ArrayCollection();

        foreach ($nombres as $nombre){
            $nombre = trim($nombre);

            $genero = $this->em->getRepository(Genero::class)->findOneBy([
                'nombre' => $nombre
            ]);

            if(empty($genero)){
                $genero = new Genero();
                $genero->setNombre($nombre);
            }

            $generos->add($genero);
        }

        return $generos;
    }

    private function createOrUpdateActor(array $nombres): Collection
    {
        $actores = new ArrayCollection();

        foreach ($nombres as $nombre) {
            $nombre = trim($nombre);

            $actor = $this->em->getRepository(Actor::class)->findOneBy([
                'nombre' => $nombre
            ]);

            if(empty($actor)){
                $actor = new Actor();
                $actor->setNombre($nombre);
            }

            $actores->add($actor);

        }

        return $actores;
    }

    private function createOrUpdateDirector(string $nombre): Director
    {
        $nombre = trim($nombre);

        $director = $this->em->getRepository(Director::class)->findOneBy([
            'nombre' => $nombre
        ]);

        if(empty($director)){

            $director = new Director();
            $director->setNombre($nombre);
        }

        return $director;
    }

    private function createOrUpdateFilm(string $titulo,
                                        \DateTime $fechaPublicacion,
                                        int $duracion,
                                        string $productora,
                                        Collection $generos,
                                        Collection $actores,
                                        Director $director): Pelicula
    {
        $pelicula = new Pelicula();
        $pelicula->setTitulo($titulo);
        $pelicula->setFechaPublicacion(!empty($datePublished) ? $datePublished : null);
        $pelicula->setDuracion($duracion);
        $pelicula->setProductora($productora);

        foreach ($generos as $genero){
            $pelicula->addGenero($genero);
        }

        foreach ($actores as $actor){
            $pelicula->addActor($actor);
        }

        $pelicula->setdirector($director);

        return $pelicula;
    }
}