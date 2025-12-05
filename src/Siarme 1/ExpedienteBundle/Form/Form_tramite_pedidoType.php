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


class Form_tramite_pedidoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** if ( empty($options['data']->getExpediente())) {
            $builder->add('expediente',null, array('label' => 'N° Pase CCOO Relacionado : (no requerido)','attr' => ['required' => false, 'placeholder' => ' NO-2021-00194962-CAT-MTRH']));
        }*/
        $builder->add('ccoo',TextareaType::class, array('label' => 'CCOO Nota del SAF: ','attr' => ['placeholder' => 'NO-2021-00194962-CAT-MTRH', 'class' => 'form-control']));
        if ($options['data']->getEstadoTramite()->getSlug() == "rectificacion") {
           $builder->add('numeroComprar', TextareaType::class, array('label'=>'CCOO Rectificación: ', 'required' => false,'attr' => array('placeholder' => 'NO-2020-00524991-CAT-DPPR#MHP', 'class' => 'form-control')));
          //$builder->add('texto', null, array('label' => 'Objeto: '));
        }
        if ($options['data']->getEstadoTramite()->getSlug() == "devuelto") {
           $builder->add('numeroNota', TextareaType::class, array('label'=>'CCOO No Concentra: ', 'required' => false,'attr' => array('placeholder' => 'NO-2020-00524991-CAT-DPPR#MHP', 'class' => 'form-control')));
          //$builder->add('texto', null, array('label' => 'Objeto: '));
        }
        $builder->add('fechaDestino', DateType::class, ['widget' => 'single_text', 'label'=> 'Fecha','attr' => array('class' => 'form-control')])
        ->add('trimestre',ChoiceType::class, array(
                    'attr' => array('class' => 'form-control'),
                    'choices' => array( '1° Trimestre'=>1, '2° Trimestre'=>2, '3° Trimestre'=>3,'4° Trimestre'=>4),
                       'label'  => 'Trimestre' ))
        //->add('estado')
       // ->add('estadoTramite')
        //->add('price', MoneyType::class, [  'divisor' => 100,]);
        //->add('PresupuestoOficial', NumberType::class, [ 'label' => 'Presupuesto: ' ])
        ->add('PresupuestoOficial', MoneyType::class, array('currency' => 'ARS','label'=> 'Presupuesto Oficial', 'attr' => array('class' => 'currency','type' => 'number','placeholder' => '1002003,17')))
         ->add('moneda',ChoiceType::class, array(
                            'attr' => array('class' => 'form-control'),
                            'choices' => array( 'PESOS'=>"PESOS", 'DOLARES'=>"DOLARES", 'EUROS'=>"EUROS"),
                            'preferred_choices' => array('PESOS'),
                            ))
        ->add('organismoOrigen', null, array('label' => 'SAF Solicitante: ','attr' => array('required' => 'required', 'class' => 'form-control')))
        ->add('rubro', null, array('label' => 'Rubro: ', 'attr' => array('required' => 'required', 'class' => 'form-control')));
        //->add('numeroNota', null, array('label' => '* N° Nota de Pedido: '))
        if (!empty($options['data']->getRubro())) {
        $builder->add('texto', null, array('label' => 'Objeto: '));
        }
        
        //->add('texto', TextareaType::class, array('label'=>'Observaciones : ', 'required' => false,'attr' => array('class' => 'form-control')))
        
    }

    /**
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
