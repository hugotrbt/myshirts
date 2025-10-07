<?php

namespace App\Repository;

use App\Entity\Member;
use App\Entity\Shirt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Shirt>
 */
class ShirtRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Shirt::class);
    }

/**
 * @return [Objet][] Returns an array of [Objet] objects for a member
 */
    public function findMemberShirts(Member $member): array
    {
            return $this->createQueryBuilder('o')
                    ->leftJoin('o.lockerRoom', 'i')
                    ->andWhere('i.member = :member')
                    ->setParameter('member', $member)
                    ->getQuery()
                    ->getResult()
            ;
    }

//    /**
//     * @return Shirt[] Returns an array of Shirt objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Shirt
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
