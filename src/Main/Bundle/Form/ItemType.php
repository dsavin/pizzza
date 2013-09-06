<?php

namespace Main\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('url')
            ->add('text', 'textarea', array(
                                           'required' => false,
                                      ))
            ->add('image', 'file', array(
                     'data_class' => 'Symfony\Component\HttpFoundation\File\File',
                     'property_path' => 'image',
                     'required' => true,
                     'label'=>'Фото'
                ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Main\Bundle\Entity\Item'
        ));
    }

    public function getName()
    {
        return 'main_bundle_itemtype';
    }
}
