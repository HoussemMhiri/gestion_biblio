<?php

namespace App\Controller\Admin;

use App\Entity\Livre;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class BookManagementController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Livre::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Book')
            ->setEntityLabelInPlural('Books')
            ->setPageTitle('index', 'Books List')
            ->setPageTitle('new', 'Create Book')
            ->setPageTitle('edit', 'Edit Book')
            ->setPageTitle('detail', 'Book Details');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('titre', 'Title'),
            NumberField::new('nbPages', 'Page Count'),
            DateField::new('dateEdition', 'Publication Date'),
            NumberField::new('nbExemplaires', 'Copies'),
            NumberField::new('prix', 'Price'),
            TextField::new('isbn', 'ISBN'),
            AssociationField::new('editeur', 'Publisher')
                ->setFormTypeOption('choice_label', 'nomEditeur'),
            AssociationField::new('categorie', 'Category')
                ->formatValue(function ($value, $entity) {
                    return $entity->getCategorie() ? $entity->getCategorie()->getDesignation() : null;
                }),
            AssociationField::new('auteurs', 'Authors'),
        ];
    }
}
