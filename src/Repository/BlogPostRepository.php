<?php

namespace App\Repository;

use App\Entity\BlogPost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method BlogPost|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlogPost|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlogPost[]    findAll()
 * @method BlogPost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogPostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlogPost::class);
    }

    public function completedClear()
    {
        $query = $this
            ->getEntityManager()
            ->createQuery("
DELETE FROM App\Entity\BlogPost a WHERE a.status = :status
")->setParameter('status', \App\Entity\BlogPost::STATUS_COMPLETED);
        return $query->execute();
    }

    public function getOrderedArticles($type, $order)
    {
        if($type == 'like') {
            return $this->getEntityManager()
                ->createQuery(
                    "SELECT a FROM App\Entity\BlogPost a ORDER BY a.likesCount  " . $order
                );
        }
        return $this->getEntityManager()
            ->createQuery(
                "SELECT a FROM App\Entity\BlogPost a ORDER BY a.id  " . $order
            );

}

}
