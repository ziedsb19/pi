<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class OffresType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nomSponsors', TextType::class,
            array("label"=>"Nom : ", "required"=>false,"attr"=>array("class"=>"form-control ", "placeholder"=>"votre nom")))
                ->add('adresse', TextType::class,
                    array("label"=>"Adresse : ", "required"=>false,"attr"=>array("class"=>"form-control ", "placeholder"=>" votre adresse")))
                ->add('eMail', EmailType::class,
                    array("label"=>"E-mail : ","required"=>false, "attr"=>array("class"=>"form-control ", "placeholder"=>" votre E-mail")))
                ->add('descriptionOffre',TextareaType::class,
                    array("label"=>"Description de l'offre : ", "required"=>false
                    ,"attr"=>array("class"=>"form-control", "placeholder"=>"description de l'offre")))
                ->add('imageFile',VichImageType::class , [
                    'required' => false,
                    'allow_delete' => true,
                    'download_link' => true,
                ]);

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UserBundle\Entity\Offres'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'userbundle_offres';
    }


}
