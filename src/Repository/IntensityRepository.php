<?php

namespace App\Repository;

use App\Entity\Intensity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Intensity>
 *
 * @method Intensity|null find($id, $lockMode = null, $lockVersion = null)
 * @method Intensity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Intensity[]    findAll()
 * @method Intensity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IntensityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Intensity::class);
    }

//    /**
//     * @return Intensity[] Returns an array of Intensity objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Intensity
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
