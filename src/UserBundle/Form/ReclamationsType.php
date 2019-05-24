<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ReclamationsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sujet', TextType::class, array('attr' => array('placeholder' => 'Sujet'),
                'constraints' => array(
                    new NotBlank(array("message" => "S'il vous plaît donner un sujet")),
                )
            ))
            ->add('contenue', TextareaType::class, array('attr' => array('placeholder' => 'Votre contenue ici'),
                'constraints' => array(
                    new NotBlank(array("message" => "S'il vous plaît fournir un contenue ici")),
                )
            ))
            ->add('reponse', TextareaType::class, array('attr' => array('placeholder' => 'Votre reponse ici'),
                'constraints' => array(
                    new NotBlank(array("message" => "S'il vous plaît fournir une reponse ici")),
                )
            ))
            ->add('utilisateurs')
        ;
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UserBundle\Entity\Reclamations'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'userbundle_reclamations';
    }

}
