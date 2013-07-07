<?php

namespace Main\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('title', 'text', array('label'=>'Заголовок', 'required' => false))
            ->add('description', 'text', array('label'=>'Описание', 'required' => false))
            ->add('keywords', 'text', array('label'=>'Ключевіе слова', 'required' => false))
            ->add('url')
            ->add('short_text', 'textarea', array('label'=>'Короткое описание', 'required' => false))
            ->add('text', 'textarea', array('label'=>'Текст', 'required' => false))
            ->add('big_text', 'textarea', array('label'=>'Большое описание', 'required' => false))
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
            'data_class' => 'Main\Bundle\Entity\Recipe'
        ));
    }

    public function getName()
    {
        return 'main_bundle_recipetype';
    }
}
