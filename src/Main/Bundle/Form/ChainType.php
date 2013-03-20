<?php

namespace Main\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ChainType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('url', 'text', array('label'=>'URL'))
            ->add('name', 'text', array('label'=>'Название'))
            ->add('site', 'text', array('label'=>'Сайт', 'required' => false))
            ->add('title', 'text', array('label'=>'<title/>'))
            ->add('description', 'textarea', array('label'=>'meta Description'))
            ->add('keywords', 'text', array('label'=>'meta Keywords', 'required' => false))

            ->add('type', 'choice', array(
                'choices'   => array(1 => 'Только доставка', 2 => 'Только заведения', 3 => 'Заведение с доставкой'),
                'required'  => true,
                'label'=>'Тип пиццерии'
            ))
            ->add('image', 'file', array(
                'data_class' => 'Symfony\Component\HttpFoundation\File\File',
                'property_path' => 'image',
                'required' => false,
                'label'=>'Логотип'
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Main\Bundle\Entity\Chain'
        ));
    }

    public function getName()
    {
        return 'main_bundle_chaintype';
    }
}
