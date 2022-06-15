<?php

namespace App\Repository;

use App\Entity\Personne;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Personne|null find($id, $lockMode = null, $lockVersion = null)
 * @method Personne|null findOneBy(array $criteria, array $orderBy = null)
 * @method Personne[]    findAll()
 * @method Personne[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Personne::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Personne $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Personne $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Personne[] Returns an array of Personne objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Personne
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    
    /* public function findPersonneByInterval($ageMin, $ageMax)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.age >= :ageMin and p.age <= :ageMax')
         //   ->setParameter('ageMin', $ageMin)
       //     ->setParameter('ageMax', $ageMax)
            ->setParameters(['ageMin'=>$ageMin, 'ageMax'=> $ageMax])    
            ->getQuery()
            ->getResult()
        ;
    }*/
    
    public function findPersonneByInterval($ageMin, $ageMax)
    {
        $qb= $this->createQueryBuilder('p');
        $this->addIntervalAge($qb, $ageMin, $ageMax);     
        return $qb->getQuery()->getResult();
    }
    public function statsPersonneByAgeInterval($ageMin, $ageMax)
    {
        $qb= $this->createQueryBuilder('p')
                  ->select('avg(p.age) as moyenneAge , count(p.id) as nombrePersonne');
            $this->addIntervalAge($qb, $ageMin, $ageMax);     
                 return $qb->getQuery()->getScalarResult() ;
    }
    
    private function addIntervalAge(QueryBuilder $qb, $ageMin,$ageMax){
        $qb->andWhere('p.age >= :ageMin and p.age <= :ageMax')
           //->setParameter('ageMin', $ageMin)
           //->setParameter('ageMax', $ageMax)
             ->setParameters(['ageMin'=>$ageMin, 'ageMax'=> $ageMax]);    
    }
}
