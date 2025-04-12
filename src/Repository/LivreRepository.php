<?php

namespace App\Repository;

use App\Entity\Livre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Livre>
 */
class LivreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livre::class);
    }

    //    /**
    //     * @return Livre[] Returns an array of Livre objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Livre
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }


    public function findByPrixSup($prix): ?Livre
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.prix > :val')
            ->setParameter('val', $prix)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByPrixPages($x, $y): ?Livre
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.prix > :x')
            ->andWhere('l.nb_pages < :y')
            ->setParameter('x', $x)
            ->setParameter('y', $y)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByPrixPages10($x, $y): ?Livre
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.prix > :x')
            ->andWhere('l.nb_pages < :y')
            ->setParameter('x', $x)
            ->setParameter('y', $y)
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByPrixPagesTrie($x, $y): ?Livre
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.prix > :x')
            ->andWhere('l.nb_pages < :y')
            ->setParameter('x', $x)
            ->setParameter('y', $y)
            ->orderBy('l.prix', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByPrixPages10Trie($x, $y): ?Livre
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.prix > :x')
            ->andWhere('l.nb_pages < :y')
            ->setParameter('x', $x)
            ->setParameter('y', $y)
            ->orderBy('l.prix', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByPrixPagesAuteurTrie($x, $y): ?array
    {
        return $this->createQueryBuilder('l')
            ->join('l.auteurs', 'a') // Jointure avec la table des auteurs
            ->andWhere('l.prix > :x')
            ->andWhere('l.nb_pages < :y')
            ->andWhere('a.nom = :auteurNom') // Condition sur le nom de l'auteur
            ->andWhere('a.prenom = :auteurPrenom') // Condition sur le nom de l'auteur
            ->setParameter('x', $x)
            ->setParameter('y', $y)
            ->setParameter('auteurNom', 'Potencier') // Paramètre pour le nom de l'auteur
            ->setParameter('auteurPrenom', 'Fabien') // Paramètre pour le nom de l'auteur
            ->orderBy('l.prix', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
