<?php

namespace PointWeb\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContactType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname', 'text', array(
                "label" => "Nom :"
            ))
            ->add('firstname', 'text', array(
                "label" => "Prénom :"
            ))
            ->add('phone', 'text', array(
                "label" => "Téléphone :"
            ))
            ->add('email', 'text', array(
                "label" => "Email :"
            ))
            ->add('address', 'text', array(
                "label" => "Adresse :"
            ))
            ->add('zipCode', 'text', array(
                "label" => "Code postal :"
            ))
            ->add('city', 'text', array(
                "label" => "Ville :"
            ))
            ->add('message', 'textarea', array(
                "label" => "Message :"
            ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PointWeb\AppBundle\Entity\Contact'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pointweb_appbundle_contact';
    }
}
