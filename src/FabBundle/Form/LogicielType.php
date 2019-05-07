<?php

namespace FabBundle\Form;

use FabBundle\Entity\FournisseurLog;
use FabBundle\Entity\TypeLog;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FabBundle\Entity\FabLab;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class LogicielType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('urlphoto',FileType::class, array('label' => 'Image(JPG)'))
            ->add('datesortie',DateType::class ,
                array(
                    'required' => false,
                    'widget' => 'single_text',
                    'label'=>"Date",
                    'attr' => [
                        'class' => 'form-control',
                        'id'=>"form_date",
                        'html5' => false,
                    ],
                ))

            ->add('prix')
            ->add('nbrlicence')
            ->add('description')
            ->add('typeLog', EntityType::class, [
             'class' => TypeLog::class,
             'choice_label' => 'libelle',])
            ->add('fournisseurLog', EntityType::class, [
            'class' => FournisseurLog::class,
            'choice_label' => 'nom',])
            ->add('fabLab', EntityType::class, [
                'class' => FabLab::class,
                'choice_label' => 'nom',] );
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FabBundle\Entity\Logiciel'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'Fabbundle_logiciel';
    }


}
