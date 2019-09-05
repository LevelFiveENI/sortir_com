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

    public function sortiAllParametre($site, $seek, $checkDate, $dateDeb,$dateFin, $checkOrga, $checkInscri, $checkNonInscri, $checkPasse, $user){
        $req = $this -> createQueryBuilder('s');


            if(!$checkPasse){
                $req
                    // on affiche les sorties pour les etats suivant (ouverte, cloture, act en cours)
                    ->join('s.etat', 'etat')
                    ->addSelect('etat')
                    ->andWhere('etat.libelle = :etaO')
                    ->setParameter('etaO' ,"ouverte")
                    ->orWhere('etat.libelle = :etaCl')
                    ->setParameter('etaCl' ,"cloture")
                    ->orWhere('etat.libelle = :etaAc')
                    ->setParameter('etaAc' ,"actEncours");

                // si l'user est co on rajoute les sorti cree et non publié
                    if($user){
                         $req
                            ->join('s.Organisateur','orga')
                            ->addSelect('orga')
                            ->orWhere('etat.libelle = :etaCr and orga = :user')
                            //->andWhere('orga = :user')
                            ->setParameter('etaCr' ,"cree")
                            ->setParameter('user' ,$user);
                    }
            }


        // recherche si un site existe
            if($site && $site!="Tous"){
                $req
                    ->join('s.site','site')
                    ->addSelect('site')
                    ->andWhere('site.nom = :site')
                    ->setParameter('site',$site);
            }
        // recherche dans le nom du titre
            if($seek){
                $seek = trim($seek);
                $req
                    ->andwhere('s.nom like :nom')
                    ->setParameter('nom', "%".$seek."%");
            }
        // si la checkbox date est cochée on recherche au niveau de la date
            if($checkDate){
                $req
                     ->andWhere('s.dateHeureDebut >= :dateSDeb')
                     ->andWhere('s.dateHeureDebut <= :dateSFin')
                    ->setParameter(':dateSDeb',$dateDeb)
                    ->setParameter(':dateSFin',$dateFin);
            }

            // si la checkbox n'est pas coché on recherche avec date du jour -1 mois
            if(!$checkDate){
                $dateJ = date('y-m-d',strtotime('-1 month'));
                $req
                    ->andWhere('s.dateHeureDebut >= :dateSDeb')
                    ->setParameter(':dateSDeb',$dateJ);

            }


            // si la checkbox orga est cochée on recherche au niveau de l'orga
            if($checkOrga) {
                $req
                    ->andWhere('s.Organisateur = :user' )
                    ->setParameter('user', $user);
            }


            // si la checkbox inscrit est cochée on recherche au niveau de l'inscription
            if($checkInscri) {
                $req
                    ->join('s.Participant', 'participant')
                    ->addSelect('participant')
                    ->andWhere('participant = :user')
                    ->setParameter('user', $user);
            }





///////////////////////////////requete non fonctionnel a modifier

            // si la checkbox non inscrit est cochée on recherche au niveau de l'inscription

            if($checkNonInscri) {
                $req
                    ->Join('s.Participant', 'participant')
                    ->addSelect('participant')
                    ->andWhere('participant != :user')
                    ->setParameter('user', $user);
            }

//////////////////////////////////////////////////////////////////////////////



            // si la checkbox passee est cochée on recherche les sorties passées
            if($checkPasse) {
                $req
                    ->join('s.etat', 'etat')
                    ->addSelect('etat')
                    ->andWhere('etat.libelle = :etat')
                    ->setParameter('etat', 'passee');
            }



        ////////////////// puis on ordonne les resultats par date
                 $req
            ->orderBy('s.dateHeureDebut','DESC');

        return $req->getQuery()->getResult();
    }




//    // recupère les données de tous les sites juste avec une date deb
//    /**
//     * @param $site
//     * @param $dateSdeb
//     */
//    public function sortieByAll($user){
//
//
//        $req = $this -> createQueryBuilder('s')
//            ->select('s')
//            ->join('s.etat', 'etat')
//            ->join('s.site','site')
//            ->addSelect('site')
//            ->addSelect('etat')
//
//            // en fonction des etats
//            ->andWhere('etat.libelle = :etaO')
//            ->setParameter('etaO' ,"ouverte")
//            ->orWhere('etat.libelle = :etaCl')
//            ->setParameter('etaCl' ,"cloture")
//            ->orWhere('etat.libelle = :etaAc')
//            ->setParameter('etaAc' ,"actEncours")
//            ->orWhere('etat.libelle = :etaP')
//            ->setParameter('etaP' ,"passee");
//
//            //en fonction de la date du jour - 1 mois
//
//        $dateJ = date('y-m-d',strtotime('-1 month'));
//        $req
//            ->andWhere('s.dateHeureDebut >= :dateSDeb')
//            ->setParameter(':dateSDeb',$dateJ);
//
//            // en fonction de l'etat créé plus l'user
//        if($user){
//            $req
//                ->join('s.Organisateur','orga')
//                ->addSelect('orga')
//                ->orWhere('etat.libelle = :etaCr and orga = :user')
//                //->andWhere('orga = :user')
//                ->setParameter('etaCr' ,"cree")
//                ->setParameter('user' ,$user);
//
//        }
//
//            // puis on ordonne les resultats par date
//        $req
//            ->orderBy('s.dateHeureDebut','DESC');
//
//
//        return $req->getQuery()->getResult();
//    }









    //////////////////// recupère les sorties en fonction de la categorie
    /**
     * @param $categorie
     * @return array
     */

    public function sortieByCategorie($id, $user){
            $req = $this -> createQueryBuilder('s')
                ->select('s')
                ->where('s.categorie = :categorie')
                ->setParameter('categorie',$id);

        $dateJ = date('y-m-d',strtotime('-1 month'));
        $req
            ->andWhere('s.dateHeureDebut >= :dateSDeb')
            ->setParameter(':dateSDeb',$dateJ);


        $req
            // on affiche les sorties pour les etats suivant (ouverte, cloture, act en cours)
            ->join('s.etat', 'etat')
            ->addSelect('etat')
            ->andWhere('etat.libelle = :etaO')
            ->setParameter('etaO' ,"ouverte")
            ->orWhere('etat.libelle = :etaCl')
            ->setParameter('etaCl' ,"cloture")
            ->orWhere('etat.libelle = :etaAc')
            ->setParameter('etaAc' ,"actEncours");


        return $req->getQuery()->getResult();
    }




    /////////////////////////Valentin !!! ***************

    public function findByDateRecent()
    {
        $dateJ = date('y-m-d',strtotime('-1 month'));

        return $this->createQueryBuilder('s')
            ->select('s')
            ->orderBy('s.dateLimiteInscription', 'ASC')
            ->where('s.etat = :ett')
            ->setParameter('ett',2)
            ->andWhere('s.dateHeureDebut >= :dateSDeb')
            ->setParameter(':dateSDeb',$dateJ)
            ->setMaxResults(3)
            ->getQuery()
            ->getResult()
            ;
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
