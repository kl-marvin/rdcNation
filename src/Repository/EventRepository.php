<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Event::class);
    }

    // /**
    //  * @return Event[] Returns an array of Event objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Event
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


    /**
     * @return Event|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findtoCome(): ?Event
    {
        $date = new \DateTime();
        $date->sub(new \DateInterval('P1D'));
        return $this->createQueryBuilder('c')
            ->where('c.date > :date')
            ->setParameter('date', $date)
            ->orderBy('c.date', 'asc')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findXtoCome($x = 4): iterable
    {
        $date = new \DateTime();
        $date->sub(new \DateInterval('P1D'));
        return $this->createQueryBuilder('c')
            ->where('c.date >= :date')
            ->setParameter('date', $date)
            ->orderBy('c.date', 'asc')
            ->setMaxResults($x)
            ->setFirstResult(1)
            ->getQuery()
            ->getResult();
    }
}
