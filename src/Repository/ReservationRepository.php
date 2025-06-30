<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 *
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function add(Reservation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Reservation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByFilters(?string $userName, ?int $areaId): array
    {
        $qb = $this->createQueryBuilder('r')
            ->innerJoin('r.user_id', 'u')
            ->innerJoin('r.area_id', 'a');

        if (!empty($userName)) {
            $qb->andWhere('u.user_name = :uname')
               ->setParameter('uname', $userName);
        }

        if (!empty($areaId)) {
            $qb->andWhere('a.id = :aid')
               ->setParameter('aid', $areaId);
        }

        return $qb->getQuery()->getResult();
    }

    public function existReservation(
        int $areaId,
        \DateTimeInterface $fecha,
        \DateTimeInterface $hourIni,
        \DateTimeInterface $hourFin
    ): bool
    {
        $dql = "
            SELECT
                COUNT(r.id)
            FROM
                App\Entity\Reservation r
            JOIN
                r.schedule_id s
            WHERE
                r.area_id = :areaId
                AND r.date = :fecha
                AND r.status IN (:statuses)
                AND (
                    :newStartTime BETWEEN s.hour_ini AND s.hour_fin
                    OR
                    :newEndTime BETWEEN s.hour_ini AND s.hour_fin
                    OR
                    s.hour_ini BETWEEN :newStartTime AND :newEndTime
                )
        ";

        $query = $this->getEntityManager()->createQuery($dql);

        // Establecer parámetros
        $query->setParameter('areaId', $areaId);
        $query->setParameter('fecha', $fecha->format('Y-m-d'));
        $query->setParameter('statuses', ['ACTIVE']);
        $query->setParameter('newStartTime', $hourIni->format('H:i:s'));
        $query->setParameter('newEndTime', $hourFin->format('H:i:s'));
        // $qb = $this->createQueryBuilder('r');

        // $qb->select('COUNT(r.id)')
        //     ->innerJoin('r.schedule_id', 's')
        //     ->where('r.area_id = :areaId')
        //     ->andWhere('r.date = :fecha')
        //     ->andWhere('r.status IN (:status)');

        // $overlapConditions = $qb->expr()->orX(
        //     $qb->expr()->between(':newStartTime', 's.hour_ini', 's.hour_fin'),
        //     $qb->expr()->between(':newEndTime', 's.hour_ini', 's.hour_fin')
        // );

        // $qb->andWhere($overlapConditions);

        // // Establecer parámetros
        // $qb->setParameter('areaId', $areaId)
        //     ->setParameter('fecha', $fecha->format('Y-m-d'))
        //     ->setParameter('status', ['ACTIVE'])
        //     ->setParameter('newStartTime', $hourIni->format('H:i:s'))
        //     ->setParameter('newEndTime', $hourFin->format('H:i:s'));

        try {
            return (int) $query->getSingleScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return 0;
        }
    }

//    /**
//     * @return Reservation[] Returns an array of Reservation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Reservation
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
