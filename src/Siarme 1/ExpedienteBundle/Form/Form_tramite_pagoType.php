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


class Form_tramite_pagoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

            if ( empty($options['data']->getExpediente())) {
                $builder->add('expediente',null, array('label' => 'EXPEDIENTE MADRE : ','attr' => ['required' => 'required', 'placeholder' => 'EX-2022-00290998-   -CAT-SCA#MEC']));
            } 
                        
            $builder->add('ccoo',TextType::class, array('label' => 'Numero Expediente de Pago: ','attr' => ['placeholder' => 'EX-2022-00290998- -CAT-SA#MS']))
                    ->add('fecha', DateType::class, ['widget' => 'single_text', 'label'=> 'Fecha de Ingreso','attr' => array('class' => 'form-control')])
                    ->add('organismoOrigen', null, array('label' => 'Ingresó De: ','attr' => array('required' => 'required', 'class' => 'form-control')))
                    ->add('proveedor', null, array('label' => 'Proveedor: ','attr' => array('required' => 'required', 'class' => 'form-control')))
                    ->add('montoAdjudica', MoneyType::class, array('label' => 'Importe O. P.', 'currency' => 'ARS', 'attr' => array('class' => 'currency','type' => 'number','placeholder' => '1002003,17')))
                    ->add('texto', TextType::class, array('label' => 'Observaciones: (no requerido) ', 'required' => false,'attr' => array( 'class' => 'form-control')));
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
