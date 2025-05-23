<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Form\LivreType;
use App\Repository\LivreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/livre')]
final class BookController extends AbstractController
{
    #[Route(name: 'app_livre_index', methods: ['GET'])]
    public function index(LivreRepository $livreRepository): Response
    {
        return $this->render('livre/index.html.twig', [
            'livres' => $livreRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_livre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $livre = new Livre();
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorie = $form->get('categorie')->getData();
            $editeur = $form->get('editeur')->getData();
            $auteurs = $form->get('auteurs')->getData();

            $livre->setCategorie($categorie);
            $livre->setEditeur($editeur);

            foreach ($auteurs as $auteur) {
                $livre->addAuteur($auteur);
            }

            $entityManager->persist($livre);
            $entityManager->flush();

            return $this->redirectToRoute('app_livre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('livre/new.html.twig', [
            'livre' => $livre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_livre_show', methods: ['GET'])]
    public function show(Livre $livre): Response
    {
        return $this->render('livre/show.html.twig', [
            'livre' => $livre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_livre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Livre $livre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorie = $form->get('categorie')->getData();
            $editeur = $form->get('editeur')->getData();
            $auteurs = $form->get('auteurs')->getData();

            $livre->setCategorie($categorie);
            $livre->setEditeur($editeur);

            // Get current authors
            $currentAuteurs = new ArrayCollection();
            foreach ($livre->getAuteurs() as $auteur) {
                $currentAuteurs->add($auteur);
            }

            // Remove authors that were unselected
            foreach ($currentAuteurs as $auteur) {
                if (!$auteurs->contains($auteur)) {
                    $livre->removeAuteur($auteur);
                }
            }

            // Add newly selected authors
            foreach ($auteurs as $auteur) {
                if (!$currentAuteurs->contains($auteur)) {
                    $livre->addAuteur($auteur);
                }
            }

            $entityManager->persist($livre);
            $entityManager->flush();

            return $this->redirectToRoute('app_livre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('livre/edit.html.twig', [
            'livre' => $livre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_livre_delete', methods: ['POST'])]
    public function delete(Request $request, Livre $livre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $livre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($livre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_livre_index', [], Response::HTTP_SEE_OTHER);
    }
}
