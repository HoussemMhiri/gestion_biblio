<?php

namespace App\Controller\Admin;

use App\Entity\Auteur;
use App\Entity\Categorie;
use App\Entity\Editeur;
use App\Entity\Livre;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $nbAuteurs = $this->entityManager->getRepository(Auteur::class)->count([]);
        $nbCategories = $this->entityManager->getRepository(Categorie::class)->count([]);
        $nbEditeurs = $this->entityManager->getRepository(Editeur::class)->count([]);
        $nbLivres = $this->entityManager->getRepository(Livre::class)->count([]);

        return $this->render('admin/index.html.twig', [
            'nbAuteurs' => $nbAuteurs,
            'nbCategories' => $nbCategories,
            'nbEditeurs' => $nbEditeurs,
            'nbLivres' => $nbLivres,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Administration de ma Bibliothèque');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('Gestion des contenus');
        yield MenuItem::linkToCrud('Auteurs', 'fas fa-user', Auteur::class);
        yield MenuItem::linkToCrud('Catégories', 'fas fa-tags', Categorie::class);
        yield MenuItem::linkToCrud('Editeurs', 'fas fa-building', Editeur::class);
        yield MenuItem::linkToCrud('Livres', 'fas fa-book', Livre::class);
    }
}