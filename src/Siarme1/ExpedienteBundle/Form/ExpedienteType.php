<?php

namespace Siarme\ExpedienteBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Siarme\ExpedienteBundle\Form\TramiteType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ExpedienteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        if ('exp_ccoo' === $options['accion']) {
            $builder->add('numeroInterno',null, array('label' => 'Número Interno: ','attr' => ['readonly'=> 'readonly']));
            $builder->add('ccoo',null, array('label' => 'N° de CCOO (pase a DPCBS): ','attr' => ['placeholder' => 'NO-2021-00029960-CAT-DPPR#MHP', 'class' => 'form-control',
        'autocomplete' => 'off']));
        }

        if ('exp_general' === $options['accion'] ) {
              $builder->add('numeroInterno',null, array('label' => 'Número Interno: ','attr' => ['readonly'=> 'readonly']));
              $builder->add('numeroGde',null, array('label' => 'Numero de Expediente GDE : ','attr' => ['placeholder' => 'EX-2021-00011124- -CAT-SCA#MHP', 'class' => 'form-control', 'autocomplete' => 'off']));
        }

        if ('exp_acuerdo' === $options['accion']) {
             $builder->add('ccoo',null, array('label' => 'CCOO Nota DPPR: ','attr' => ['placeholder' => 'NO-2021-00029960-CAT-DPPR#MHP', 'class' => 'form-control',
        'autocomplete' => 'off']));
             $builder->add('numeroGde',null, array('label' => 'Numero de Expediente GDE: ','attr' => ['placeholder' => 'EX-2021-00011124- -CAT-SCA#MHP', 'class' => 'form-control',
        'autocomplete' => 'off']))
                     ->add('fechaDesde', DateType::class, ['widget' => 'single_text', 'label'=> 'Fecha de Inicio', 'attr' => ['class' => 'form-control']])
                     ->add('fechaHasta', DateType::class, ['widget' => 'single_text', 'label'=> 'Fecha de Finalizacion', 'attr' => ['class' => 'form-control']]);
        }
       // ->add('departamentoRm', null, array('label'=>'Reparticion : ', 'attr' => array('class' => 'form-control')))
        $builder->add('extracto', TextareaType::class,  array('label'=>'Descripcion : ', 'attr' => array('class' => 'form-control','placeholder' => 'Descripcion del objeto de contratación','autocomplete' => 'off')));
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Siarme\ExpedienteBundle\Entity\Expediente',
            'accion' => "general",
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
