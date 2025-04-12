<?php

namespace App\Controller\Admin;

use App\Entity\Auteur;
use App\Entity\Categorie;
use App\Entity\Editeur;
use App\Entity\Livre;
use App\Repository\LivreRepository;
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
        $entityCounts = [
            'nbAuteurs' => Auteur::class,
            'nbCategories' => Categorie::class,
            'nbEditeurs' => Editeur::class,
            'nbLivres' => Livre::class,
        ];

        $counts = [];
        foreach ($entityCounts as $key => $entityClass) {
            $counts[$key] = $this->entityManager->getRepository($entityClass)->count([]);
        }

        $livreRepository = $this->entityManager->getRepository(Livre::class);
        $recentLivres = $livreRepository->findBy([], ['id' => 'DESC'], 3);

        return $this->render('admin/index.html.twig', array_merge($counts, [
            'recentLivres' => $recentLivres,
        ]));
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Library Management System');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Home', 'fa fa-home');
        yield MenuItem::section('Content Management');

        yield MenuItem::subMenu('Manage', 'fas fa-cogs')->setSubItems([
            MenuItem::linkToCrud('Authors', 'fas fa-user', Auteur::class),
            MenuItem::linkToCrud('Categories', 'fas fa-tags', Categorie::class),
            MenuItem::linkToCrud('Publishers', 'fas fa-building', Editeur::class),
            MenuItem::linkToCrud('Books', 'fas fa-book', Livre::class),
        ]);
    }
}
