<?php

namespace Siarme\AusentismoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Siarme\AusentismoBundle\Entity\Licencia;


class LicenciaDocType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('fechaDesde', DateType::class,['widget' => 'single_text'])
                ->add('dias',IntegerType::class, ['required' => true, 'attr' => ['class' => 'form-control']])
                ->add('fechaHasta', DateType::class, ['widget' => 'single_text', 'attr' => ['readonly'=> 'readonly', 'class' => 'form-control']])              
                ->add('articulo', null, ['required' => false, 'attr' => ['placeholder' => 'Buscar','class' => 'form-control']])
                ->add('enfermedad',null,['required' => false, 'attr' => ['placeholder' => 'Buscar','class' => 'form-control']]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Licencia::class,
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
