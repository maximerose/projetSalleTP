<?php

namespace App\Form;

use App\Entity\Marque;
use App\Entity\Ordinateur;
use App\Repository\MarqueRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrdinateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ip')
            ->add('numero')
            ->add('marque', EntityType::class, [
                'class' => Marque::class,
                'query_builder' => function (MarqueRepository $repo) {
                    return $repo->createQueryBuilder('m')
                        ->where('length(m.nom) <= 4');
                }
            ])
            ->add('salle');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ordinateur::class,
        ]);
    }
}
