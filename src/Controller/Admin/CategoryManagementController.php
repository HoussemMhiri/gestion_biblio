<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CategoryManagementController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Categorie::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Category')
            ->setEntityLabelInPlural('Categories')
            ->setPageTitle('index', 'Categories List')
            ->setPageTitle('new', 'Create Category')
            ->setPageTitle('edit', 'Edit Category')
            ->setPageTitle('detail', 'Category Details');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('designation', 'Name'),
            TextField::new('description', 'Description'),
        ];
    }
}
