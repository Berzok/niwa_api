<?php

namespace App\Repository;

use App\Entity\TagType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TagType|null find($id, $lockMode = null, $lockVersion = null)
 * @method TagType|null findOneBy(array $criteria, array $orderBy = null)
 * @method TagType[]    findAll()
 * @method TagType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagTypeRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, TagType::class);
    }

    /**
     * @param TagType $entity
     * @param bool $flush
     * @return void
     */
    public function add(TagType $entity, bool $flush = true): void {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param TagType $entity
     * @param bool $flush
     * @return void
     */
    public function remove(TagType $entity, bool $flush = true): void {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return TagType[] Returns an array of TagType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TagType
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
