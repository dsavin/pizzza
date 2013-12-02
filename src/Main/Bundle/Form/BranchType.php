<?php

namespace Main\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Main\Bundle\Entity\Branch;

class BranchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('street', 'text', array('label' => 'адрес'))
            ->add('rating', 'integer', array('label' => 'Рейтинг'))
            ->add('title', 'text', array('label' => 'Заголовок', 'required' => false))
            ->add('description', 'text', array('label' => 'Дескрипшин', 'required' => false))
            ->add('keywords', 'text', array('label' => 'Ключевые слова', 'required' => false))
            ->add('url', 'text', array('label' => 'Урл'))
            ->add('phones', 'text', array('label' => 'Телефоны', 'required' => false))
            ->add('metro', 'text', array('label' => 'Метро', 'required' => false))
            ->add('text', 'textarea', array(
                                           'label' => 'Описание заведения',
                                           'required' => false,
                                           'attr' => array(
                                               'class' => 'tinymce',
                                               'data-theme' => 'bbcode',
                                               'style' => 'height: 500px;'
                                           )
                                      ))
            ->add('lat', 'number', array('precision'=>6))
            ->add('lng', 'number', array('precision'=>6))
            ->add('work_at', 'text', array('label' => 'Режим работы', 'required' => false))
            ->add('social_text', 'text', array('label' => 'Текст для социалок', 'required' => false))
            ->add('features', 'entity', array(
                                             'label'         => 'Особенности',
                                             'property'      => 'alt_teg',
                                             'multiple'      => true,
                                             'required'      => false,
                                             'class'         => 'MainBundle:Feature',
                                             'query_builder' => function (EntityRepository $er) {
                                                 return $er->createQueryBuilder('f')
                                                     ->where(' f.status = 1');
                                             },
                                             'attr'          => array(
                                                 'class' => 'chosen'
                                             )
                                        ))
            ->add('status', 'choice', array(
                                           'choices'   => array(
                                               Branch::STATUS_ACTIVE => 'Активное',
                                               Branch::STATUS_DELETED => 'Удаленное',
                                               Branch::STATUS_CLOSED => 'Закрытое',
                                               Branch::STATUS_REPAIR => 'На ремонте'
                                           ),
                                           'required'  => false,
                                           'label'=>'Статус'
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
