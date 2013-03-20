<?php

namespace Main\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BranchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('street')
            ->add('rating')
            ->add('title')
            ->add('description')
            ->add('keywords')
            ->add('url')
            ->add('phones')
            ->add('text')
            ->add('lat')
            ->add('lng')
            ->add('work_at')
            ->add('social_text')
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
