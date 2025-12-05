<?php

namespace Siarme\ExpedienteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class Form_tramite_procesoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
            //if ( !empty($options['data']->getExpediente())) {
                $builder->add('expediente',null, array('label' => 'N° EXP o CCOO Relacionado : ','attr' => ['required' => 'required', 'placeholder' => '100-0013-CDI20']));
            //}
        $builder
        //->add('fechaDestino', DateType::class, ['widget' => 'single_text', 'label'=> 'Fecha'])
       /** ->add('trimestre',ChoiceType::class, array(
                    'choices' => array( '1° Trimestre'=>1, '2° Trimestre'=>2, '3° Trimestre'=>3,'4° Trimestre'=>4),
                       'label'  => 'Trimestre' ))*/
        ->add('numeroComprar',null, array('label' => 'Numero Proceso: ','attr' => ['placeholder' => '100-0013-CDI20']))

        ->add('tipoProceso', null, array('label' => 'Procedimiento ', 'attr' => array('class' => 'form-control')))
        ->add('tipo',ChoiceType::class, array(
                    'attr' => array('class' => 'form-control'),
                    'choices' => array( '-'=>"-", 'Compulsa Abreviada'=>"Compulsa Abreviada", 'Adjudicacion Simple'=>"Adjudicación Simple"),
                    'label'  => 'Tipo',
                    'preferred_choices' => array('-'), ))
        ->add('modalidad');
        if ( !empty($options['data']->getId())) {
         $builder->add('montoAdjudica', MoneyType::class, array('label' => 'Monto Adjudicado ', 'currency' => 'ARS', 'attr' => array('class' => 'currency','type' => 'number','placeholder' => '1002003,17')))
                ->add('moneda',ChoiceType::class, array(
                            'attr' => array('class' => 'form-control'),
                            'choices' => array( 'PESOS'=>"PESOS", 'DOLARES'=>"DOLARES", 'EUROS'=>"EUROS"),
                            'preferred_choices' => array('PESOS'),
                            ));  
        } 
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Siarme\ExpedienteBundle\Entity\Tramite'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'siarme_expedientebundle_tramite';
    }


}
