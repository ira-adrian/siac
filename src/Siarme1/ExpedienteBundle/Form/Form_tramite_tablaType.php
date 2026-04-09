<?php

namespace Siarme\ExpedienteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class Form_tramite_tablaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        //->add('fechaDestino', DateType::class, ['widget' => 'single_text', 'label'=> 'Fecha'])
       /** ->add('trimestre',ChoiceType::class, array(
                    'choices' => array( '1° Trimestre'=>1, '2° Trimestre'=>2, '3° Trimestre'=>3,'4° Trimestre'=>4),
                       'label'  => 'Trimestre' ))*/
        ->add('ccoo',TextType::class, array('label' => 'Tabla a Actualizar: ','attr' => ['placeholder' => 'ACTUALICAR TABLA ...', 'class' => 'form-control']))
        //->add('modalidad')
       // ->add('tipoProceso')
       // ->add('estadoTramite')
        //->add('price', MoneyType::class, [  'divisor' => 100,]);
        //->add('PresupuestoOficial', NumberType::class, [ 'label' => 'Presupuesto: ' ])
        //->add('PresupuestoOficial', MoneyType::class, array('attr' => array('class' => 'corto')))
        //->add('organismoOrigen', null, array('label' => 'SAF Solicitante: '))
        //->add('organismoDestino')->add('tipoTramite')
        //->add('departamentoRm')->add('expediente')
        //->add('clase', null, array('label' => 'SubRubro: '))
        ->add('texto', TextareaType::class, array('label'=>'* Observaciones : ', 'required' => false,'attr' => array('class' => 'form-control')));
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
