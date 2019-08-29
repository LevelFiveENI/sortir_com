<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Ville;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LieuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('rue')
            ->add('latitude')
            ->add('longitude')
            /*
            ->add('nomVille', EntityType::class,[
                'class' => Lieu::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('l')->leftJoin('l.nomVille', 'nomVille')->addSelect('nomVille');
                    //return $er->createQueryBuilder('v')->orderBy('v.nom', 'ASC');
                },
                'choice_label' => 'nomVille',
            ])
            */
            ->add('nomVille', EntityType::class,[
                'class' => Ville::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('v')->orderBy('v.nom', 'ASC');
                },
                'choice_label' => 'nom',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,
        ]);
    }
}
