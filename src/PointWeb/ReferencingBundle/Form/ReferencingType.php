<?php

namespace PointWeb\ReferencingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReferencingType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array(
                "label" => "Titre :",
                'attr' => array(
                    'class' => 'form-control counter',
                    'data-limit' => '75'
                ),
                'label_attr' => array('class' => 'col-lg-2 control-label'),
            ))
            ->add('h1', 'text', array(
                "label" => "H1 :",
                'attr' => array('class' => 'form-control counter'),
                'label_attr' => array('class' => 'col-lg-2 control-label'),
            ))
            ->add('h1Title', 'text', array(
                "label" => "Title du H1 :",
                'attr' => array('class' => 'form-control counter'),
                'label_attr' => array('class' => 'col-lg-2 control-label'),
            ))
            ->add('description', 'textarea', array(
                "label" => "Description de la page",
                'attr' => array('class' => 'form-control counter',
                    'data-limit' => '170'
                ),
                'required' => false,
                'label_attr' => array('class' => 'col-lg-2 control-label'),
            ))
            ->add('path', 'text', array(
                "label" => "Chemin vers la page (par exemple \"/\" pour la page d'accueil, \"/contact\" pour la page contact) :",
                'attr' => array('class' => 'form-control'),
                'label_attr' => array('class' => 'col-lg-2 control-label'),
            ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PointWeb\ReferencingBundle\Entity\Referencing'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pointweb_referencingbundle_referencing';
    }
}
