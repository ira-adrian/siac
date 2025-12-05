<?php

namespace Siarme\ExpedienteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Siarme\ExpedienteBundle\Form\DatoATType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ExpedienteGdeEditType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('numeroGde',null, array('label' => 'Numero de Expediente GDE: ','attr' => ['placeholder' => 'EX-2021-00011124- -CAT-SCA#MHP', 'class' => 'form-control',
        'autocomplete' => 'off']))
                ->add('extracto', TextareaType::class,  array('label'=>'Descripcion del Proceso : ', 'attr' => array('class' => 'form-control',
        'autocomplete' => 'off')));

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
