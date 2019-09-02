<?php

namespace App\Repository;

use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{

    // recupère les sorties en fonction du site
    /**
     * @param $site
     * @return array
     */
    public function sortieBySite($site){
        $req = $this -> createQueryBuilder('s')
            ->select('s')->addSelect('site')
            ->join('s.site','site')
            ->where('site.nom = :sit')
            ->setParameter('sit',$site)
            ->orderBy('s.dateHeureDebut','DESC');

        return $req->getQuery()->getArrayResult();
    }



// on effectue une recherche dans le titre de la sortie
    /**
     * @param $search
     * @return array
     */
public function sortieBySearch($site, $search){
    // on enleve les espaces
    $searchT = trim($search);

    $req = $this -> createQueryBuilder('s')
        ->select('s')->addSelect('site')
        ->join('s.site','site')
        ->where('site.nom = :sit')
        ->andwhere('s.nom like :nom')
        ->setParameter('sit',$site)
        ->setParameter('nom', "%".$searchT."%")
        ->orderBy('s.dateHeureDebut','DESC');

    return $req->getQuery()->getArrayResult();

}

















    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    // /**
    //  * @return Sortie[] Returns an array of Sortie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sortie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
