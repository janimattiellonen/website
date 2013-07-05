<?php

namespace Jme\ArticleBundle\Form\Type;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface,
    Xi\Bundle\TagBundle\Form\Type\TagType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text')
                ->add('brief', 'textarea')
                ->add('content', 'textarea')
                ->add('tags', 'tag')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jme\ArticleBundle\Entity\Article',
        ));
    }

    public function getName()
    {
        return 'article';
    }
}
