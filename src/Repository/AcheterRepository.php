<?php

namespace App\Repository;

use App\Entity\Acheter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Acheter>
 *
 * @method Acheter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Acheter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Acheter[]    findAll()
 * @method Acheter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AcheterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Acheter::class);
    }

//    /**
//     * @return Acheter[] Returns an array of Acheter objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Acheter
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
