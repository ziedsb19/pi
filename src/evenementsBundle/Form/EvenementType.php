<?php

namespace evenementsBundle\Form;

use Gregwar\CaptchaBundle\Type\CaptchaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class EvenementType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('titre', TextType::class,
            array("label"=>"titre de l'evenement", "required"=>false,"attr"=>array("class"=>"form-control ", "placeholder"=>"titre de l'evenement")))
            ->add('date', DateTimeType::class ,
                array("widget"=>"single_text", "html5"=>false ,"label"=>"date de l'evenement","required"=>false, "attr"=>array("class"=>"form-control ","id"=>"form_date")))
            ->add('imageFile', VichImageType::class,
                array("label" => "image de l'evenement" ,"required"=>false, "allow_delete"=>true, "attr"=>array("class"=>"form-control-file", "accept"=>"image/*")))
            ->add('description', TextareaType::class,
                array("label"=>"description de l'evenement", "required"=>false
                ,"attr"=>array("class"=>"form-control", "placeholder"=>"description de l'evenement", "rows"=>"4")))
            ->add('prix', TextType::class,
                array("label"=>"prix de l'evenement", "required"=>false ,"attr"=>array("class"=>"form-control", "placeholder"=>"prix")))
            ->add('adresse', TextType::class,
                array("label"=>"adresse de l'evenement","required"=>false, "attr"=>array("class"=>"form-control ", "placeholder"=>"adresse de l'evenement")))
            ->add('billetsRestants', NumberType::class,
                array("label"=>"billets de l'evenement", "required"=>false ,"attr"=>array("class"=>"form-control", "placeholder"=>"billets de l'evenement")))
            ->add('categories', EntityType::class, array(
                "class"=>"evenementsBundle:Categorie",
                "choice_label"=>"nom",
                "multiple"=>true,
                "expanded"=>true
            ))
            ->add('captcha', CaptchaType::class ,
                array("label"=>"je ne suis pas un robot" ,"reload"=>false, "as_url"=>true, "attr"=>array("classe"=>"form-control")))
            ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'evenementsBundle\Entity\Evenement'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'evenementsbundle_evenement';
    }


}
