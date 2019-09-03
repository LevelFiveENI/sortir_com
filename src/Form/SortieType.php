<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\Categorie;
use App\Entity\Ville;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;


class SortieType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                "trim" => true,
                "label" => "Nom de la sortie",
                "required"=>false,
            ])
            ->add('dateHeureDebut', DateTimeType::class, [
                'input'=>"datetime",
                'format' => 'dd-MM-yyyy',
                "required"=>false,
                //'data' => new \DateTime("now"),

            ] )
            ->add('duree', IntegerType::class, [
                "required"=>false,
            ])
            ->add('dateLimiteInscription', DateTimeType::class, [
                'input'=>"datetime",
                'format' => 'dd-MM-yyyy',
                "required"=>false,

            ])
            ->add('nbInscriptionsMax', IntegerType::class, [
                "required"=>false,
            ])
            ->add('infosSortie', TextareaType::class, [
                'required'=> false,
            ])

            ->add('categorie', EntityType::class,[
                'class' => Categorie::class,
                'query_builder' => function(EntityRepository $er){
                return $er->createQueryBuilder('c')->orderBy('c.libelle', 'ASC');
                },
                'choice_label' => 'libelle',

            ])
            ->add('site', EntityType::class,[
                'class' => Site::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('s')->orderBy('s.nom');
                },
                'choice_label' => 'nom',

            ])
            ->add('ville', EntityType::class,[
                'class' => Ville::class,
                'placeholder'=>'Choisir une ville',
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('v')->orderBy('v.nom', 'ASC');
                },
                'choice_label' => 'nom',
            ])

            ->add('Publier', SubmitType::class)
            ->add('Enregistrer', SubmitType::class)
            ->add('Supprimer', SubmitType::class)
            ->add('Annuler', SubmitType::class)
        ;


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }

}
