<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContactoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name','text',array(
                'label'=>'Nombre',
                'attr'=>array('class'=>'form','placeholder'=>'nombre'),
                'required'=>true
                ))
            ->add('email','email',array(
                'label'=>'Email',
                'attr'=>array('class'=>'form','placeholder'=>'email'),
                
                ))
            ->add('phone','text',array('label'=>'Telefono','attr'=>array('class'=>'form','placeholder'=>'telefono')))  
            ->add('company','text',array('label'=>'Empresa','attr'=>array('class'=>'form','placeholder'=>'empresa o razon social')))
            ->add('body','textarea',array('label'=>'Direccion','attr'=>array('class'=>'form_area','placeholder'=>'Direccion del contacto')))
        ;
    }
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Contacto'
        ));
    }
    public function getName()
    {
        return 'contacto';
    }
}

