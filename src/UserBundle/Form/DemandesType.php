<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class DemandesType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('titre', TextType::class,
            array("label"=>"Titre", "required"=>false,"attr"=>array("class"=>"form-control ", "placeholder"=>"titre de la demande")))
                ->add('descriptionEvenement', TextareaType::class,
                    array("label"=>"Description de l'evenement", "required"=>false
                    ,"attr"=>array("class"=>"form-control", "placeholder"=>"description de l'evenement")))
                ->add('date',DateType::class ,
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
            ->add('eMail', EmailType::class,
                array("label"=>"E-mail","required"=>false, "attr"=>array("class"=>"form-control ", "placeholder"=>"E-mail de l'organisateur")))
            ->add('descriptionOrganisateur' ,TextareaType::class,
                    array("label"=>"Description de l'organisateur", "required"=>false
                    ,"attr"=>array("class"=>"form-control", "placeholder"=>"description de l'organisateur")))
            ->add('imageFile',VichImageType::class , [
                'required' => false,
                'allow_delete' => true,
                'download_link' => true,
                'attr'=>array("class"=>"form-control-file", "accept"=>"image/*")
            ]);

        $builder->get('date')->addModelTransformer(new CallbackTransformer(
            function ($value) {
                if(!$value) {
                    return new \DateTime('now +1 month');
                }
                return $value;
            },
            function ($value) {
                return $value;
            }
        ));

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UserBundle\Entity\Demandes'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'userbundle_demandes';
    }


}
