<?php

namespace Siarme\ExpedienteBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ExpedienteEditType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

            $builder->add('tipoExpediente', null, ['label'=> 'Tipo:', 'attr' => ['class' => 'form-control']]);
       
            $builder->add('ccoo',null, array('label' => 'CCOO Nota DPPR: ','attr' => ['placeholder' => 'NO-2021-00029960-CAT-DPPR#MHP', 'class' => 'form-control']))
                     ->add('numeroGde',null, array('label' => 'Numero de Expediente GDE: ','attr' => ['placeholder' => 'EX-2021-00011124- -CAT-SCA#MHP', 'class' => 'form-control']));
            if ( "exp_acuerdo"  === $options['data']->getTipoExpediente()->getSlug() ) {
                $builder->add('fechaDesde', DateType::class, ['widget' => 'single_text', 'label'=> 'Fecha de Inicio', 'attr' => ['class' => 'form-control']])
                        ->add('fechaHasta', DateType::class, ['widget' => 'single_text', 'label'=> 'Fecha de Finalizacion', 'attr' => ['class' => 'form-control']]);
            }
            $builder->add('extracto', TextareaType::class,  array('label'=>'Descripcion del Proceso : ', 'attr' => array('class' => 'form-control')));




    }


    /**
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
