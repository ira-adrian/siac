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


class TramiteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('numeroTramite',null, array('label' => 'Numero de PEDIDO Interno: ','attr' => ['readonly'=> 'readonly', 'class' => 'form-control']))
        ->add('ccoo',null, array('label' => 'CCOO Nota SAF: ','attr' => ['placeholder' => 'NO-2021-00194962-CAT-MTRH', 'class' => 'form-control', 'autocomplete' => 'off']))
        ->add('fechaDestino', DateType::class, ['widget' => 'single_text', 'label'=> 'Fecha', 'attr' => ['class' => 'form-control']])
        ->add('trimestre',ChoiceType::class, array('expanded' => false,'multiple' => false,
                    'choices' => array( '1° Trimestre'=>1, '2° Trimestre'=>2, '3° Trimestre'=>3,'4° Trimestre'=>4),
                       'label'  => 'Trimestre', 'attr' => array('required' => 'required', 'class' => 'form-control') ))
        //->add('estado')
       // ->add('estadoTramite')
        //->add('price', MoneyType::class, [  'divisor' => 100,]);
        //->add('PresupuestoOficial', NumberType::class, [ 'label' => 'Presupuesto: ' ])
        ->add('PresupuestoOficial', MoneyType::class, array('currency' => 'ARS', 'attr' => array('type' => 'number','placeholder' => '1002003,17','class' => 'form-control', 'autocomplete' => 'off')))
        ->add('organismoOrigen', null, array('label' => 'SAF Solicitante: ','attr' => array('required' => 'required','class' => 'form-control')))
        //->add('organismoDestino')->add('tipoTramite')
        //->add('departamentoRm')->add('expediente')
        ->add('rubro', null, array('label' => 'Rubro: ', 'attr' => array('required' => 'required','class' => 'form-control')))
        //->add('texto', TextareaType::class, array('label'=>'Observaciones : ', 'required' => false,'attr' => array('class' => 'form-control')))
        ;
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
