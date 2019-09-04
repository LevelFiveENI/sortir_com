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

    // recupère les données de tous les sites juste avec une date deb
    /**
     * @param $site
     * @param $dateSdeb
     */
    public function sortieByAll( $dateSdeb){
        $req = $this -> createQueryBuilder('s')
            ->select('s')
            ->join('s.etat', 'etat')
            ->join('s.site','site')
            ->addSelect('site')
            ->addSelect('etat')
            ->where('s.dateHeureDebut >= :dateSDeb')
            ->setParameter(':dateSDeb',$dateSdeb)
            ->orderBy('s.dateHeureDebut','DESC');

        return $req->getQuery()->getArrayResult();
    }




    // recupère les sorties en fonction du site
    /**
     * @param $site
     * @return array
     */
    public function sortieBySite($site, $dateSdeb, $dateSfin){
        $req = $this -> createQueryBuilder('s')
            ->select('s')
            ->join('s.etat', 'etat')
            ->join('s.site','site')
            ->addSelect('site')
            ->addSelect('etat')
            ->where('site.nom = :sit')
            ->andWhere('s.dateHeureDebut >= :dateSDeb')
            ->andWhere('s.dateHeureDebut <= :dateSFin')
            ->setParameter('sit',$site)
            ->setParameter(':dateSDeb',$dateSdeb)
            ->setParameter(':dateSFin',$dateSfin)
            ->orderBy('s.dateHeureDebut','DESC');

        return $req->getQuery()->getArrayResult();
    }


    // recupère les sorties en fonction de la categorie
    /**
     * @param $categorie
     * @return array
     */
    public function sortieByCategorie($categorie){
            $req = $this -> createQueryBuilder('s')
                ->select('s')
                ->where('s.categorie = :categorie')
                ->setParameter('categorie',$categorie);

            return $req->getQuery()->getResult();
    }



// on effectue une recherche dans le titre de la sortie
    /**
     * @param $search
     * @return array
     */
public function sortieBySearch($site, $search, $dateSdeb, $dateSfin){
    // on enleve les espaces
    $searchT = trim($search);

    $req = $this -> createQueryBuilder('s')
        ->select('s')
        ->join('s.site','site')
        ->join('s.etat', 'etat')
        ->addSelect('etat')
        ->addSelect('site')
        ->where('site.nom = :sit')
        ->andwhere('s.nom like :nom')
        ->andWhere('s.dateHeureDebut >= :dateSDeb')
        ->andWhere('s.dateHeureDebut <= :dateSFin')
        ->setParameter('sit',$site)
        ->setParameter('nom', "%".$searchT."%")
        ->setParameter(':dateSDeb',$dateSdeb)
        ->setParameter(':dateSFin',$dateSfin)
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
