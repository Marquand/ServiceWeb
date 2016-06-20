<?php

namespace PointWeb\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MenuType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array(
                "label" => "Titre * :",
                'attr' => array('class' => 'form-control'),
                'label_attr' => array('class' => 'col-lg-2 control-label'),
            ))
            ->add('description', 'textarea', array(
                "label" => "Description :",
                'required' => false,
                'attr' => array(
                    'class' => 'tinymce form-control',
                    'data-theme' => 'advanced'
                ),
                'label_attr' => array('class' => 'col-lg-2 control-label'),
            ))
            ->add('path', 'text', array(
                "label" => "Path * :",
                'attr' => array('class' => 'form-control'),
                'label_attr' => array('class' => 'col-lg-2 control-label'),
            ))
            ->add('position', 'integer', array(
                "label" => "Position :",
                'attr' => array('class' => 'form-control'),
                'label_attr' => array('class' => 'col-lg-2 control-label'),
                'required' => false
            ))
            ->add('parent', 'entity', array(
                'class' => 'PointWebAdminBundle:Menu',
                'empty_value' => 'Racine',
                'label' => 'Menu parent :',
                'required' => false,
                'attr' => array('class' => 'form-control'),
                'label_attr' => array('class' => 'col-lg-2 control-label'),
            ))
            ->add('pictures', 'collection', array(
                'label' => 'Images',
                'type' => new FileType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'required' => false
            ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PointWeb\AdminBundle\Entity\Menu'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pointweb_adminbundle_menu';
    }
}
