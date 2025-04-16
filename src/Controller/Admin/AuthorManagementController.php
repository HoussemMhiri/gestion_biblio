<?php

namespace App\Controller\Admin;

use App\Entity\Auteur;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AuthorManagementController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Auteur::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Author')
            ->setEntityLabelInPlural('Authors')
            ->setPageTitle('index', 'Authors List')
            ->setPageTitle('new', 'Create Author')
            ->setPageTitle('edit', 'Edit Author')
            ->setPageTitle('detail', 'Author Details');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('prenom', 'First Name'),
            TextField::new('nom', 'Last Name'),
            TextField::new('biographie', 'Biography'),
        ];
    }
}
