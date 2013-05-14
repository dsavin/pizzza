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
            ->add('text', 'textarea', array('label'=>'Описание', 'required' => false))
            ->add('social_text', 'text', array('label'=>'Текст для социалок', 'required' => false))
            ->add('type', 'choice', array(
                'choices'   => array(3 => 'Заведение с доставкой', 1 => 'Только доставка', 2 => 'Только заведения'),
                'required'  => true,
                'label'=>'Тип пиццерии'
            ))
            ->add('recommend', 'choice', array(
                                         'choices'   => array(0 => 'Нет', 1 => 'Да'),
                                         'required'  => true,
                                         'label'=>'Рекомендуэм?'
                                    ))
            ->add('image', 'file', array(
                'data_class' => 'Symfony\Component\HttpFoundation\File\File',
                'property_path' => 'image',
                'required' => false,
                'label'=>'Логотип'
            ))
            ->add('rating_delivery', 'integer', array('label'=>'рейтиг доставки'))
            ->add('title_delivery', 'text', array('label'=>'<title/> - доставки', 'required' => false))
            ->add('description_delivery', 'textarea', array('label'=>'meta Description - доставки', 'required' => false))
            ->add('keywords_delivery', 'text', array('label'=>'meta Keywords - доставки', 'required' => false))
            ->add('text_delivery', 'textarea', array('label'=>'Текст - доставки', 'required' => false))
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
