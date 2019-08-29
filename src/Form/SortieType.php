<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\Categorie;
use App\Entity\Ville;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Tests\Fixtures\Validation\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

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
                'data' => new \DateTime(),

            ] )
            ->add('duree', IntegerType::class, [

                "required"=>false,

            ])
            ->add('dateLimiteInscription', DateTimeType::class, [
                'input'=>"datetime",
                'format' => 'dd-MM-yyyy',
                "required"=>false,
                'data' => new \DateTime(),
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
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('v')->orderBy('v.nom', 'ASC');
                },
                'choice_label' => 'nom',
            ])

            ->add('lieu', EntityType::class,[
                'class' => Lieu::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('l')
                        ->leftJoin('l.nomVille', 'nomVille')->addSelect('nomVille')
                        ->where('l.nomVille = :ville ')
                        ->setParameter('ville', 'Le Mans');
                },
                'choice_label' => 'nom',
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
