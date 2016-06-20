<?php

namespace PointWeb\PdfBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PdfType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pdfName', 'text', array(
                'label' => 'Titre du pdf',
                'attr' => array('class' => 'form-control'),
                'label_attr' => array('class' => 'col-lg-2 control-label'),
            ))
            ->add('target', 'choice', array(
                'label' => 'Destination',
                'required' => true,
                'placeholder' => false,
                'choices' => $options['target'],
                'attr' => array('class' => 'form-control'),
                'label_attr' => array('class' => 'col-lg-2 control-label'),
            ));
        if ($options['edit_form'] == false) {
            $builder
                ->add('file', 'file', array(
                    'label' => 'Fichier PDF',
                    'data_class' => null,
                    'attr' => array('class' => 'form-control'),
                    'label_attr' => array('class' => 'col-lg-2 control-label'),
                ));
        } else {
            $builder
                ->add('file', 'file', array(
                    'label' => 'Fichier PDF',
                    'data_class' => null,
                    'required' => false,
                    'attr' => array('class' => 'form-control'),
                    'label_attr' => array('class' => 'col-lg-2 control-label'),
                ));
        };
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PointWeb\PdfBundle\Entity\Pdf',
            'edit_form' => false,
            'target' => array(
                'home' => "Page d'accueil",
                'contact' => 'Page Contact',
                'autre' => 'Autre Page'
            )
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pointweb_pdfbundle_pdf';
    }
}
