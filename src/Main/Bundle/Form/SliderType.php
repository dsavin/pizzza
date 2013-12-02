<?php

namespace Main\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SliderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('label'=>'Название'))
            ->add('title', 'text', array('label'=>'Заглавление', 'required' => false))
            ->add('text', 'text', array('label'=>'Текст', 'required' => false))
            ->add('url', 'text', array('label'=>'Ссылка', 'required' => false))
            ->add('image', 'file', array(
                                        'data_class' => 'Symfony\Component\HttpFoundation\File\File',
                                        'property_path' => 'image',
                                        'required' => false,
                                        'label'=>'Картинка'
                                   ))
            ->add('target', 'choice', array(
                                           'choices'   => array(0 => 'В этом окне', 1 => 'В новом окне'),
                                           'required'  => false,
                                           'label'=>'Открытие ссылки'
                                      ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Main\Bundle\Entity\Slider'
        ));
    }

    public function getName()
    {
        return 'main_bundle_slidertype';
    }
}
