<?php

namespace App\Controller\Admin;

use App\Entity\Editeur;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PublisherManagementController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Editeur::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Publisher')
            ->setEntityLabelInPlural('Publishers')
            ->setPageTitle('index', 'Publishers List')
            ->setPageTitle('new', 'Create Publisher')
            ->setPageTitle('edit', 'Edit Publisher')
            ->setPageTitle('detail', 'Publisher Details');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('nomEditeur', 'Name'),
            TextField::new('pays', 'Country'),
            TextField::new('adresse', 'Address'),
            TextField::new('telephone', 'Phone'),
        ];
    }
}
