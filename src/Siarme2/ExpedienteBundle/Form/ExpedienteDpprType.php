<?php

namespace Siarme\ExpedienteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ExpedienteDpprType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('numeroInterno',null, array('label' => 'NUMERO INTERNO: ','attr' => ['readonly'=> 'readonly']))
        ->add('ccoo',null, array('label' => 'CCOO Nota DPPR: ','attr' => ['placeholder' => 'NO-2021-00029960-CAT-DPPR#MHP', 'class' => 'form-control',
        'autocomplete' => 'off']))
       // ->add('ccoo',null, array('label' => 'CCOO Nota DPPR: ','attr' => ['readonly'=> 'readonly', 'placeholder' => 'NO-2021-00029960-CAT-DPPR#MHP']))
       // ->add('departamentoRm', null, array('label'=>'Reparticion : ', 'attr' => array('class' => 'form-control')))
        ->add('extracto', TextareaType::class,  array('label'=>'Descripcion : ', 'attr' => array('class' => 'form-control','placeholder' => 'Descripcion del objeto de contratacion',
        'autocomplete' => 'off')))
       // ->add('agente')
        //->add('relacionados')
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Siarme\ExpedienteBundle\Entity\Expediente'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'siarme_expedientebundle_expediente';
    }


}
