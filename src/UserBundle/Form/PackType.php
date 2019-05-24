<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PackType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nomPack', TextType::class,
            array("label"=>"Nom du Pack : ", "required"=>false,"attr"=>array("class"=>"form-control ", "placeholder"=>"nom du pack")))
                ->add('descriptionPack', TextareaType::class,
                    array("label"=>"Description du Pack", "required"=>false
                    ,"attr"=>array("class"=>"form-control", "placeholder"=>"description du Pack")))
                ->add('prix')
                ->add('nbParticipants')
                ->add('dateLimite',DateType::class ,
                    array(
                        'required' => false,
                        'widget' => 'single_text',
                        'label'=>"Date",
                        'attr' => [
                            'class' => 'form-control',
                            'id'=>"form_date",
                            'html5' => false,
                        ],
                    ));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UserBundle\Entity\Pack'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'userbundle_pack';
    }


}
