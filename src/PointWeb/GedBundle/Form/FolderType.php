<?php

namespace PointWeb\GedBundle\Form;

use PointWeb\AdminBundle\Form\FileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FolderType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                "label" => "Nom :",
                'attr' => array('class' => 'form-control'),
                'label_attr' => array('class' => 'col-lg-2 control-label'),
            ))
            ->add('description', 'textarea', array(
                "label" => "Description :",
                'required' => false,
                'attr' => array('class' => 'tinymce form-control'),
                'label_attr' => array('class' => 'col-lg-2 control-label'),
            ))
            ->add('users', 'entity', array(
                'class' => 'PointWebUserBundle:User',
                'property' => 'fullname',
                'empty_value' => 'Choisissez un ou plusieurs utilisateurs',
                'label' => 'Utilisateurs :',
                'required' => false,
                'multiple' => true,
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
            'data_class' => 'PointWeb\GedBundle\Entity\Folder'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pointweb_gedbundle_folder';
    }
}
