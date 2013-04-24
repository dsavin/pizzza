<?php

namespace Main\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class BranchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('street', 'text', array('label' => 'адрес'))
            ->add('rating', 'integer', array('label' => 'Рейтинг','data' => 0))
            ->add('title', 'text', array('label' => 'Заголовок','required' => false, 'data'=>'New Title Street'))
            ->add('description', 'text', array('label' => 'Дескрипшин','required' => false, 'data'=>'New Description Street'))
            ->add('keywords', 'text', array('label' => 'Ключевые слова','required' => false, 'data'=>'New Keywords Street'))
            ->add('url', 'text', array('label' => 'Урл'))
            ->add('phones', 'text', array('label' => 'Телефоны','required' => false))
            ->add('metro', 'text', array('label' => 'Метро','required' => false))
            ->add('text', 'textarea', array('label' => 'Описание заведения','required' => false))
            ->add('lat')
            ->add('lng')
            ->add('work_at', 'text', array('label' => 'Режим работы','required' => false))
            ->add('social_text', 'text', array('label' => 'Текст для социалок','required' => false))
            ->add('features', 'entity', array(
                        'label' => 'Особенности',
                        'property' => 'alt_teg',
                        'multiple' => true,
                        'required' => false,
                        'class' => 'MainBundle:Feature',
                        'query_builder' => function(EntityRepository $er) {
                            return $er->createQueryBuilder('f')
                                ->where(' f.status = 1');
                        },
                        'attr' => array(
                            'class' => 'chosen'
                        )
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
