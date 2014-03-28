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
            ->add('name', 'text', array('required' => true, 'label'=>'Название'))
            ->add('price', 'integer', array('required' => false, 'label'=>'Цена'))
            ->add('size', 'text', array('required' => false, 'label'=>'Размер'))
            ->add('url', 'text', array('required' => true, 'label'=>'УРЛ'))
            ->add('text', 'textarea', array('required' => false))
            ->add('image', 'file', array(
                     'data_class' => 'Symfony\Component\HttpFoundation\File\File',
                     'property_path' => 'image',
                     'required' => false,
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
