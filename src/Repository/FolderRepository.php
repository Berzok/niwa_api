<?php

namespace App\Repository;

use App\Entity\Folder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Folder|null find($id, $lockMode = null, $lockVersion = null)
 * @method Folder|null findOneBy(array $criteria, array $orderBy = null)
 * @method Folder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FolderRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Folder::class);
    }

    /**
     * @return Folder[]
     */
    public function findAll(): array {
        $q = $this->createQueryBuilder('f')
            ->where('f.id != :id_root')
            ->setParameter('id_root', 0)
            ->getQuery();

        return $q->getResult();
    }

    /**
     * @param string $name
     * @return string
     */
    public function findByName(string $name): string {
        $em = $this->getEntityManager();
        return $em->getRepository(Folder::class)->createQueryBuilder('f')
            ->where('f.name LIKE :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Image[] Returns an array of Image objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Image
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
