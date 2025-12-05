<?php

namespace Siarme\DocumentoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TipoDocumentoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('slug')->add('nombreDocumento')->add('numero')->add('anio')->add('visible')->add('esArchivo')->add('membrete')->add('glyphicon')->add('rol')->add('tipoTramite')->add('estadoTramite')->add('texto');
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Siarme\DocumentoBundle\Entity\TipoDocumento'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'siarme_documentobundle_tipodocumento';
    }


}
