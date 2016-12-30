<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class UserForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class)
            ->add('password', PasswordType::class)
            ->add('dni', TextType::class)
            ->add('name', TextType::class)
            ->add('last_name', TextType::class)
            ->add('email', EmailType::class)
            ->add('tipo', ChoiceType::class, array(
                    'choices'  => array(
                        'Recepcionista' => 'Recepcionista',
                        'Gerente' => 'Gerente',
                        'Administrador' => 'Administrador',
                    ),
                ))
            ->add('active', CheckboxType::class, array('required' => false))
            ->add('telefono', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Guardar'));
    }
}
