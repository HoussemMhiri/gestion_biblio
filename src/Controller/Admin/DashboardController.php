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
        // Calculate total counts for entities
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

        // Calculate total inventory value
        $qb = $this->entityManager->createQueryBuilder();
        $totalValue = $qb->select('SUM(l.prix * l.nbExemplaires)')
            ->from(Livre::class, 'l')
            ->getQuery()
            ->getSingleScalarResult() ?? 0;

        // Get books by category statistics
        $qbCategory = $this->entityManager->createQueryBuilder();
        $booksByCategory = $qbCategory->select('c.designation', 'COUNT(l.id) as bookCount')
            ->from(Livre::class, 'l')
            ->join('l.categorie', 'c')
            ->groupBy('c.id', 'c.designation')
            ->getQuery()
            ->getResult();

        // Get total publishers
        $totalPublishers = $this->entityManager->getRepository(Editeur::class)->count([]);

        // Count books with authors
        $qbAuthors = $this->entityManager->createQueryBuilder();
        $booksWithAuthors = $qbAuthors->select('COUNT(DISTINCT l.id)')
            ->from(Livre::class, 'l')
            ->where('SIZE(l.auteurs) > 0')
            ->getQuery()
            ->getSingleScalarResult() ?? 0;

        // Count books with publishers
        $qbPublishers = $this->entityManager->createQueryBuilder();
        $booksWithPublishers = $qbPublishers->select('COUNT(l.id)')
            ->from(Livre::class, 'l')
            ->where('l.editeur IS NOT NULL')
            ->getQuery()
            ->getSingleScalarResult() ?? 0;

        // Count books with categories
        $qbCategories = $this->entityManager->createQueryBuilder();
        $booksWithCategories = $qbCategories->select('COUNT(l.id)')
            ->from(Livre::class, 'l')
            ->where('l.categorie IS NOT NULL')
            ->getQuery()
            ->getSingleScalarResult() ?? 0;

        // Calculate percentages
        $totalBooks = $counts['nbLivres'] ?: 1; // Avoid division by zero
        foreach ($booksByCategory as &$category) {
            $category['percentage'] = round(($category['bookCount'] / $totalBooks) * 100, 2);
        }

        return $this->render('admin/index.html.twig', array_merge($counts, [
            'totalValue' => $totalValue,
            'booksByCategory' => $booksByCategory,
            'totalPublishers' => $totalPublishers,
            'booksWithAuthors' => round(($booksWithAuthors / $totalBooks) * 100, 2),
            'booksWithPublishers' => round(($booksWithPublishers / $totalBooks) * 100, 2),
            'booksWithCategories' => round(($booksWithCategories / $totalBooks) * 100, 2),
            'recentLivres' => $this->entityManager->getRepository(Livre::class)->findBy([], ['dateEdition' => 'DESC'], 5),
        ]));
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Library Management System');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fas fa-book');
        yield MenuItem::linkToUrl('Home', 'fa fa-home', '/livre');
        yield MenuItem::section('Content Management');



        yield MenuItem::subMenu('Manage', 'fas fa-cogs')->setSubItems([
            MenuItem::linkToCrud('Authors', 'fas fa-user', Auteur::class),
            MenuItem::linkToCrud('Categories', 'fas fa-tags', Categorie::class),
            MenuItem::linkToCrud('Publishers', 'fas fa-building', Editeur::class),
            MenuItem::linkToCrud('Books', 'fas fa-book', Livre::class),
        ]);
    }
}
