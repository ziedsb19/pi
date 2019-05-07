<?php

namespace FabBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\File;
use FabBundle\Entity\FabLab;

class FabLabType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class ,array("required"=>false))
            ->add('adresse',TextType::class ,array("required"=>false))
            ->add('ville',TextType::class ,array("required"=>false))

            ->add('image', FileType::class, array('label' => 'Image' , "required" => False))
            ->add('numerotel',NumberType::class ,array("required"=>false))
            ->add('description',TextType::class ,array("required"=>false))
            ->add('responsable',TextType::class ,array("required"=>false))
        ->add("valider",SubmitType::class);

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FabBundle\Entity\FabLab'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'Fabbundle_fablab';
    }


}
