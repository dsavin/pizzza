<?php

namespace Main\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class BranchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('street')
            ->add('rating')
            ->add('title')
            ->add('description')
            ->add('keywords')
            ->add('url')
            ->add('phones')
            ->add('text')
            ->add('lat')
            ->add('lng')
            ->add('work_at')
            ->add('social_text')
//            ->add('features', 'entity', array(
//                      'class' => 'MainBundle:Feature',
//
//                 ))
            ->add('features', 'entity', array(
                        'label' => 'Особенности',
                        'property' => 'alt_teg',
                        'multiple' => true,
                        'required' => false,
                        'class' => 'MainBundle:Feature',
                        'query_builder' => function(EntityRepository $er) {
                            return $er->createQueryBuilder('f')
                                ->where(' f.status = 1');
                        },
                        'attr' => array(
                            'class' => 'chosen'
                        )
                   ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Main\Bundle\Entity\Branch'
        ));
    }

    public function getName()
    {
        return 'main_bundle_branchtype';
    }
}
