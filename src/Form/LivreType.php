<?php

namespace App\Form;

use App\Entity\Livre;
use App\Entity\Auteur;
use App\Entity\Categorie;
use App\Entity\Editeur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('nbPages', NumberType::class, [
                'label' => 'Nombre de pages',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('dateEdition', DateType::class, [
                'label' => 'Date d\'édition',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('nbExemplaires', NumberType::class, [
                'label' => 'Nombre d\'exemplaires',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('prix', NumberType::class, [
                'label' => 'Prix',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('isbn', TextType::class, [
                'label' => 'ISBN',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('editeur', EntityType::class, [
                'class' => Editeur::class,
                'choice_label' => 'nomEditeur',
                'label' => 'Éditeur',
                'attr' => ['class' => 'form-control'],
                'required' => true,
                'placeholder' => 'Sélectionnez un éditeur',
                'help' => '<a href="/editeur/new" class="btn btn-link">Ajouter un nouvel éditeur</a>',
                'help_html' => true,
            ])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'designation',
                'label' => 'Catégorie',
                'attr' => ['class' => 'form-control'],
                'required' => true,
                'placeholder' => 'Sélectionnez une catégorie',
                'help' => '<a href="/categorie/new" class="btn btn-link">Ajouter une nouvelle catégorie</a>',
                'help_html' => true,
            ])
            ->add('auteurs', EntityType::class, [
                'class' => Auteur::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Auteurs',
                'attr' => ['class' => 'form-control'],
                'required' => true,
                'help' => '<a href="/auteur/new" class="btn btn-link">Ajouter un nouvel auteur</a>',
                'help_html' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livre::class,
        ]);
    }
}
