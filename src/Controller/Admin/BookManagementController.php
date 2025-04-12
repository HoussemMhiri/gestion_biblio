<?php

namespace App\Controller\Admin;

use App\Entity\Livre;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;


class BookManagementController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Livre::class;
    }
}
