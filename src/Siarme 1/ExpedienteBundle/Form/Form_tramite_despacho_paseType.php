<?php

namespace Siarme\ExpedienteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Siarme\AusentismoBundle\Form\ProveedorType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class Form_tramite_despacho_paseType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
 
             $builder->add('fechaDestino', DateType::class, ['widget' => 'single_text', 'label'=> 'Fecha de Pase','attr' => array('class' => 'form-control')])
                    ->add('organismoDestino', null, array('label' => 'Pase A: ','attr' => array('required' => 'required', 'class' => 'form-control')))
                    ->add('montoAdjudica', MoneyType::class, array('label' => 'Monto Adjudicado', 'currency' => 'ARS', 'attr' => array('class' => 'currency','type' => 'number','placeholder' => '1002003,17')))
                    ->add('moneda',ChoiceType::class, array(
                            'attr' => array('class' => 'form-control'),
                            'choices' => array( 'PESOS'=>"PESOS", 'DOLARES'=>"DOLARES", 'EUROS'=>"EUROS"),
                            'label'  => 'Monedas',
                            'preferred_choices' => array('PESOS'),
                            ));
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
