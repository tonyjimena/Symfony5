<?php

namespace App\Repository;

use App\Entity\Projects;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;



/**
 * @method Projects|null find($id, $lockMode = null, $lockVersion = null)
 * @method Projects|null findOneBy(array $criteria, array $orderBy = null)
 * @method Projects[]    findAll()
 * @method Projects[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Projects::class);
        $this->manager = $manager;
    }

     /**
      * @return Projects[] Returns an array of Projects objects
      */
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
    public function findOneBySomeField($value): ?Projects
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function saveProject($name, $place, $info, $imageFile){
        
        $newProject = new Projects();

        $newProject
                ->setName($name)
                ->setInfo($info)
                ->setPlace($place)
                ->setImage($imageFile);

        $this->manager->persist($newProject);
        $this->manager->flush();
        
        $newID = $newProject->getId();

        return $newID;
    } 

    public function lastId(){

        $conn = $this->manager->getConnection();

        $sql = '
            SELECT * FROM projects p
            ORDER BY p.id DESC LIMIT 1
            ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    
        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    } 
}


//select id from projects order by id DESC limit 1;