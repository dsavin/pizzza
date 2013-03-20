<?php

namespace Main\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DiscountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('url')
            ->add('name')
            ->add('title')
            ->add('description')
            ->add('keywords')
            ->add('short_text')
            ->add('type', 'choice', array(
                'choices'   => array(1 => 'Вся сеть', 2 => 'Не во всех заведениях'),
                'required'  => true,
                'label'=>'Действует в'
            ))
            ->add('text')
            ->add('time_work')
            ->add('image', 'file', array(
                'data_class' => 'Symfony\Component\HttpFoundation\File\File',
                'property_path' => 'image',
                'required' => false,
                'label'=>'Картинка'
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Main\Bundle\Entity\Discount'
        ));
    }

    public function getName()
    {
        return 'main_bundle_discounttype';
    }
}
