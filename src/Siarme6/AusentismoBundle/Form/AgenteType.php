<?php

namespace Siarme\AusentismoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Siarme\AusentismoBundle\Form\CargoType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;

class AgenteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('apellidoNombre')
        ->add('cuil',TextType::class,  ['attr' => ['placeholder' => 'Ingresar sin "-" ni "/"   ejemplo: 20362585423','class' => 'form-control']])
        ->add('fechaNacimiento', DateType::class,['widget' => 'single_text'])
        ->add('fechaInicioLaboral', DateType::class,['widget' => 'single_text'])
        ->add('domicilio',TextType::class , ['required' => false,'attr' => ['class' => 'form-control']])
        ->add('localidad')
        ->add('telefonoMovil',TelType::class , ['required' => false,'attr' => ['placeholder' => 'Ejemplo: 3834313233', 'class' => 'form-control']])
        ->add('email', EmailType::class, ['required' => false,'attr' => ['class' => 'form-control']])
        ->add('cargo', CollectionType::class, array('label' => false,
            'entry_type' => CargoType::class,
            'entry_options' => array('label' => false),
        ))
        ;
        
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Siarme\AusentismoBundle\Entity\Agente'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'siarme_ausentismobundle_agente';
    }


}
