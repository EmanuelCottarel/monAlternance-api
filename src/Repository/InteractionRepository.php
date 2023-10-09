<?php

namespace App\Repository;

use App\Dto\Interaction\Read\InteractionReadDto;
use App\Entity\Application;
use App\Entity\Interaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Interaction>
 *
 * @method Interaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Interaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Interaction[]    findAll()
 * @method Interaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InteractionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Interaction::class);
    }

    public function save(Interaction $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Interaction $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getInteractionsByApplication(Application $application)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.application = :app')
            ->setParameter('app', $application)
            ->orderBy('i.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function getInteractionsByApplicationFormat(Application $application)
    {
        $result = $this->createQueryBuilder('int')
            ->select(sprintf(
                'NEW %s(  
		        type.title,
		        DATE_FORMAT(int.date, :dateFormat),
		        int.title
		        )',
                InteractionReadDto::class
            ))
            ->join('int.type', 'type')
            ->andWhere('int.application = :app')
            ->setParameters(["dateFormat" => "%Y-%m-%d", 'app' => $application])
            ->orderBy('int.date', 'DESC')
            ->getQuery()
            ->getResult();

        return $result;
    }

//    /**
//     * @return Interaction[] Returns an array of Interaction objects
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

//    public function findOneBySomeField($value): ?Interaction
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
