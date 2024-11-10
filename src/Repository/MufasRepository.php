<?php

namespace App\Repository;

use App\Entity\Mufas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Mufas>
 */
class MufasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mufas::class);
    }

    public function findSitiosInZonal($zonal): array
        {
            return $this->createQueryBuilder('u')
                ->select('u.sitio')
                ->where('u.zonal = :zonal')
                ->setParameter('zonal', $zonal)
                ->distinct()
                ->getQuery()
                ->getResult()
            ;
        }

    public function findCablesInSitiosAndZonal($zonal,$sitio): array
        {
            return $this->createQueryBuilder('u')
                ->select('u.cable')
                ->where('u.zonal = :zonal')
                ->andWhere('u.sitio = :sitio')
                ->setParameter('zonal', $zonal)
                ->setParameter('sitio', $sitio)
                ->distinct()
                ->getQuery()
                ->getResult()
            ;
        }
    
     public function findUniqueZonal(): array
        {
            return $this->createQueryBuilder('u')
                ->select('u.zonal')
                ->distinct()
                ->getQuery()
                ->getResult()
            ;
        }
    
    public function findAllMufasWithZonalSitioCable($zonal,$sitio,$cable): array
        {
            return $this->createQueryBuilder('u')
                ->where('u.zonal = :zonal')
                ->andWhere('u.sitio = :sitio')
                ->andWhere('u.cable = :cable')
                ->setParameter('zonal', $zonal)
                ->setParameter('sitio', $sitio)
                ->setParameter('cable', $cable)
                ->getQuery()
                ->getResult()
            ;
        }





//    /**
//     * @return Mufas[] Returns an array of Mufas objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Mufas
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
