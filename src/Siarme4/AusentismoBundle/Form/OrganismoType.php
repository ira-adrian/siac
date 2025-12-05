<?php

namespace Siarme\AusentismoBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Siarme\AusentismoBundle\Form\SafType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrganismoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
         $builder->add('organismo', null, array( 'label'=>"Nombre del SAF", 'attr' => array('class' => 'form-control')))
                 ->add('clasificacion', null, array( 'label'=>"Director", 'attr' => array('class' => 'form-control')))
                 ->add('codigoGde', null, array( 'label'=>"Codigo GDE", 'attr' => array('class' => 'form-control')))
                 ->add('saf', SafType::class, array( 'label'=>false))
                 ->add('activo', null, array( 'label'=>"Es Activo"));
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Siarme\AusentismoBundle\Entity\Organismo'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'siarme_ausentismobundle_organismo';
    }


}
