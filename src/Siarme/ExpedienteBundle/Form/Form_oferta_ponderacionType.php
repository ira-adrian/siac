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


class Form_oferta_ponderacionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('pFPlazoEntrega', NumberType::class, array('label' => 'Puntaje asignado al oferente por plazo de entrega','attr' => array('type' => 'number','placeholder' => '5', 'required' => 'required', 'class' => 'form-control')))
                ->add('pFAntecedente', NumberType::class, array('label' => 'Puntaje asignado al oferente por Antecedente','attr' => array('type' => 'number','placeholder' => '3', 'required' => 'required', 'class' => 'form-control')))
                ->add('plazo', NumberType::class, array('label' => 'Días de plazo de entrega fijado por el Oferente','attr' => array('type' => 'number','placeholder' => '3', 'required' => 'required', 'class' => 'form-control')));

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
