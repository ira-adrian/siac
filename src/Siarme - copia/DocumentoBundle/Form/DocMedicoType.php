<?php

namespace Siarme\DocumentoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Siarme\AusentismoBundle\Form\LicenciaDocType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class DocMedicoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('texto', TextareaType::class,  array( 'label'=>false, 'attr' => array('class' => 'form-control'))      
          ) 
                ->add('anio', null ,  array('label' => "Corresponde al Año",'required' => true) )
                ->add('Licencia', LicenciaDocType::class);
               // ->add('medicoAuditor',null,array('label'=>'Médicos Auditores:', 'attr' => array('class' => 'form-control')))
              //  ->add('estado', CheckboxType::class,  array('label' => "Enviado a GDE",'required' => false) );;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Siarme\DocumentoBundle\Entity\Documento'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'siarme_documentobundle_docmedico';
    }


}
