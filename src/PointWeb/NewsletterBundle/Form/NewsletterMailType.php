<?php

namespace PointWeb\NewsletterBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NewsletterMailType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('newsletterGroup', 'entity', array(
                'class' => 'PointWebNewsletterBundle:NewsletterGroup',
                'property' => 'name',
                'empty_value' => ' ',
                'label' => 'Choix du groupe :',
                'required' => false,
                'attr' => array('class' => 'form-control'),
                'label_attr' => array('class' => 'col-lg-2 control-label'),
            ))
            ->add('email', 'text', array(
                "label" => "Adresse Email * :",
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
            'data_class' => 'PointWeb\NewsletterBundle\Entity\NewsletterMail'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pointweb_newsletterbundle_newslettermail';
    }
}
