<?php

namespace App\Repository;

use App\Entity\Jointure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Jointure|null find($id, $lockMode = null, $lockVersion = null)
 * @method Jointure|null findOneBy(array $criteria, array $orderBy = null)
 * @method Jointure[]    findAll()
 * @method Jointure[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JointureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Jointure::class);
    }

    // /**
    //  * @return Jointure[] Returns an array of Jointure objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Jointure
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
