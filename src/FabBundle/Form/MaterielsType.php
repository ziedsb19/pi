<?php

namespace FabBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use FabBundle\Entity\CatgMateriel;
use FabBundle\Entity\FabLab;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class MaterielsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('description')

            ->add('image', FileType::class, array('label' => 'Image(JPG)'))
            ->add('prix')
            ->add('stock')

            ->add('catgMateriel', EntityType::class, [
                'class' => CatgMateriel::class,
                'choice_label' => 'libelle', ])
            ->add('fabLab', EntityType::class, [
        'class' => FabLab::class,
                'choice_label' => 'nom',] );
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FabBundle\Entity\Materiels'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'Fabbundle_materiels';
    }


}
