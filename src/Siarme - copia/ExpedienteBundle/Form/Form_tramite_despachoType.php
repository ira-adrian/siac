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


class Form_tramite_despachoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

            if ( empty($options['data']->getExpediente())) {
                $builder->add('expediente',null, array('label' => 'N° EXP o CCOO Relacionado : ','attr' => ['required' => 'required', 'placeholder' => '100-0013-CDI20']));
            } 
                        
            $builder->add('fecha', DateType::class, ['widget' => 'single_text', 'label'=> 'Fecha de Ingreso','attr' => array('class' => 'form-control')])
                    ->add('organismoOrigen', null, array('label' => 'Ingresó De: ','attr' => array('required' => 'required', 'class' => 'form-control')))
                    ->add('texto', TextType::class, array('label' => 'Motivo: ', 'attr' => array('class' => 'form-control')))
                    ->add('numeroComprar',null, array('label' => 'Numero Proceso: ','attr' => ['placeholder' => '100-0013-CDI20']));
                    
            if ( !empty($options['data']->getNumeroComprar())) {
                $builder->add('sistema',ChoiceType::class, array(
                            'attr' => array('class' => 'form-control'),
                            'choices' => array( 'COMPRAR'=>"COMPRAR", 'BIONEXO'=>"BIONEXO", 'Ninguno SCA'=>"SCA"),
                            'label'  => 'Sistema Informático',
                            ))
                        ->add('tipoProceso', null, array('label' => 'Procedimiento ', 'attr' => array('class' => 'form-control')))
                        ->add('tipo',ChoiceType::class, array(
                            'attr' => array('class' => 'form-control'),
                            'choices' => array( '-'=>"-", 'Compulsa Abreviada'=>"Compulsa Abreviada", 'Adjudicacion Simple'=>"Adjudicación Simple"),
                            'label'  => 'Tipo',
                            'preferred_choices' => array('-'), ))
                        ->add('modalidad');
            } 
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
