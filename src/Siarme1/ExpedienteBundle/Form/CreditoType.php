<?php

namespace Siarme\ExpedienteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class CreditoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('fecha', DateType::class, ['widget' => 'single_text', 'label'=> 'Fecha', 'attr' => ['class' => 'form-control']])
                ->add('texto',TextareaType::class, array('label' => 'Descripcion ','attr' => ['placeholder' => 'EX-2020-00306215-DA#HSJB (Incremento)', 'class' => 'form-control']))
                ->add('monto', MoneyType::class, array( 'label'=> 'Monto Solicitado', 'currency' => 'ARS', 'attr' => array('type' => 'number','placeholder' => '1002003,17','class' => 'form-control')))
                ->add('estado', CheckboxType::class, [ 'label'  => 'Con Credito', 'required' => false]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Siarme\ExpedienteBundle\Entity\Credito'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'siarme_expedientebundle_credito';
    }


}
