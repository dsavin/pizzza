<?php

namespace Main\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class IngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('alt_teg')
            ->add('image', 'file', array(
                'data_class'    => 'Symfony\Component\HttpFoundation\File\File',
                'property_path' => 'image',
                'required'      => false,
                'label'         => 'Фото'
            ))
            ->add('status', 'choice', array(
                'choices'  => array(1 => 'Показать', 0 => 'Скрыть'),
                'required' => true,
                'label'    => 'Статус'
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Main\Bundle\Entity\Ingredient'
        ));
    }

    public function getName()
    {
        return 'main_bundle_ingredienttype';
    }
}
