<?php

namespace Siarme\AusentismoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class LicenciaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder ->add('articulo', null, ['required' => true, 'attr' => ['placeholder' => 'Buscar','class' => 'form-control']])
                ->add('enfermedad',null,['required' => true, 'attr' => ['placeholder' => 'Buscar','class' => 'form-control']])
                ->add('fechaDesde',DateType::class,['widget' => 'single_text'])
                ->add('fechaHasta',DateType::class,['widget' => 'single_text'])
                ->add('diagnostico');

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Siarme\AusentismoBundle\Entity\Licencia'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'siarme_ausentismobundle_licencia';
    }


}
