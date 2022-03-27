<?php

namespace App\Controller\Admin;

use App\Entity\Pelicula;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PeliculaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Pelicula::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('titulo'),
            DateField::new('fechaPublicacion'),
            IntegerField::new('duracion'),
            TextField::new('productora'),
            AssociationField::new('generos'),
            AssociationField::new('actores'),
            AssociationField::new('director'),
        ];
    }
}
