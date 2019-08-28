<?php

namespace App\Form;

use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\Categorie;
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
            ->add('lieu', TextType::class, [
                'label'=>'Nom du lieu',
                'required'=>false,
                'mapped'=>false,

            ])
            ->add('rue', TextType::class, [
                'label'=>'Adresse du lieu',
                'required'=>false,
                'mapped'=>false,

            ])
            ->add('ville', TextType::class, [
                'label'=>'Ville',
                'required'=>false,
                'mapped'=>false,

            ])
            ->add('codePostal', TextType::class, [
                'label'=>'Code Postal',
                'required'=>false,
                'mapped'=>false,

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
