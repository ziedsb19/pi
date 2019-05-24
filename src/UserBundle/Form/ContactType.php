<?php


namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('attr' => array('placeholder' => 'Votre nom'),
                'constraints' => array(
                    new NotBlank(array("message" => "Merci d'indiquer votre nom")),
                )
            ))
            ->add('subject', TextType::class, array('attr' => array('placeholder' => 'Sujet'),
                'constraints' => array(
                    new NotBlank(array("message" => "S'il vous plaît donner un sujet")),
                )
            ))
            ->add('email', EmailType::class, array('attr' => array('placeholder' => 'Votre addresse email '),
                'constraints' => array(
                    new NotBlank(array("message" => "S'il vous plaît fournir un email valide")),
                    new Email(array("message" => "Votre email ne semble pas être valide")),
                )
            ))
            ->add('message', TextareaType::class, array('attr' => array('placeholder' => 'Votre message ici'),
                'constraints' => array(
                    new NotBlank(array("message" => "S'il vous plaît fournir un message ici")),
                )
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'error_bubbling' => true
        ));
    }

    public function getName()
    {
        return 'contact_form';
    }

}