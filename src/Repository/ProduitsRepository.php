<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Produits;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Entity\Category;

/**
 * @method Produits|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produits|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produits[]    findAll()
 * @method Produits[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Produits::class);
        $this->paginator = $paginator;
    }

   
    /**
     * recupere les produits de la recherche
     * @return PaginationInterface
     */

    public function findSearch(SearchData $search): PaginationInterface
    {  
        $query = $this
        
            ->createQueryBuilder('p')
            ->select('c', 'p')
            ->join('p.categories', 'c');

        if(!empty($search->q)) {
            $query = $query
                ->andWhere('p.titre LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }

        if(!empty($search->min)) {
            $query = $query
                ->andWhere('p.prix >= :min')
                ->setParameter('min', $search->min);
        }

        if(!empty($search->max)) {
            $query = $query
                ->andWhere('p.prix <= :max')
                ->setParameter('max', $search->max);
        }

        if(!empty($search->promo)) {
            $query = $query
                ->andWhere('p.promo != 0');
        }

        if(!empty($search->category)) {
            $query = $query
                ->andWhere('c.id IN (:categories)')
                ->setParameter('categories', $search->category);
        }
        $query = $query->getQuery();
        return $this->paginator->paginate(
            $query,
            $search->page,
            6
        );
    }

    /**
     * Recherche les produits en fonction de la recherche
     */
    public function search(string $mots = NULL)
    {
        $query = $this->createQueryBuilder('p');
        $query->where('p.actif = 1');
         /* if($mots !== null){
                
            $query->andWhere('MATCH_AGAINST(p.titre, p.description) AGAINST(:mots boolean)>0')
                ->setParameter('mots', $mots);
              
            $query->andWhere('p.titre LIKE :mots OR p.description LIKE :mots OR p.couleur LIKE :mots OR p.taille LIKE :mots');
            $query->setParameter('mots', '%'.$mots.'%'); 
        }*/
        return $query->getQuery()->getResult();
    }

    public function findByPromo()
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.promo > :val')
            ->setParameter('val',0)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Produits[] Returns an array of Produits objects
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
    public function findOneBySomeField($value): ?Produits
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
