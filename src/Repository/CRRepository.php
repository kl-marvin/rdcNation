<?php

namespace App\Repository;

use App\Entity\CR;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CR|null find($id, $lockMode = null, $lockVersion = null)
 * @method CR|null findOneBy(array $criteria, array $orderBy = null)
 * @method CR[]    findAll()
 * @method CR[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CRRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CR::class);
    }

    // /**
    //  * @return CR[] Returns an array of CR objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */


    /**
     * @return CR|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findLast(): ?CR
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.id', 'asc')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

}
