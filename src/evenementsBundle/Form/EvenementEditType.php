<?php

namespace evenementsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class EvenementEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('prix')->remove('billetsRestants')->remove('captcha');
    }

    public function getParent()
    {
        return EvenementType::class;
    }
}