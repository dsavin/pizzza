<?php

namespace Main\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Main\Bundle\Entity\City;
use Main\Bundle\Entity\Lang;
use Doctrine\ORM\EntityRepository;
class CityType extends AbstractType
{



    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('label'=>'Название'))
            ->add('name_en', 'text', array('label'=>'Название EN'))
            ->add('url', 'text', array('label'=>'УРЛ'))
//            ->add('lang', 'choice', array(
//                    'choices'   => Lang::getLangsArray(),
//                    ''
//                ))
//            ->add('parent', 'entity', array(
//                'class' => 'MainBundle:City',
//                'query_builder' => function(EntityRepository $er) {
//                    return $er->createQueryBuilder('c')
//                        ->select(array('c.name'))
//                        ->orderBy('c.name', 'ASC');
//                })
//            )
        ;
//        $builder->add('parent', 'entity', array(
//            'class' => 'Main\Bundle\Entity\City',
//            'property' => 'name',
//            'empty_value' => 'Все',
//            'required' => false,
//            'query_builder' => function(EntityRepository $er) use ($options) {
//                return $er->createQueryBuilder('c')->where('c.parent = NULL');
//            },
//            'label' => 'Город'
//        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Main\Bundle\Entity\City'
        ));
    }

    public function getName()
    {
        return 'main_bundle_citytype';
    }
}
