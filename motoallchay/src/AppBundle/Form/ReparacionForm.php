<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class ReparacionForm extends AbstractType
{
    // Formulario para el modelo Reparacion, con este podemos verificar 
    // que los datos sean los correctos cuando se envian a rregistrar

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('placa', TextType::class)
            ->add('estado', ChoiceType::class, array(
                    'choices'  => array(
                        'Pendiente' => 'Pendinte',
                        'Entregado' => 'Entregado',
                    ),
                ))
            ->add('servicios', ChoiceType::class, array(
                    'choices'  => array(
                        'Cambio de llanta' => 'Cambio de llanta',
                        'Cambio de aceite' => 'Cambio de aceite',
                        'Cambio de batería' => 'Cambio de batería',
                        'Cambio de frenos' => 'Cambio de frenos',
                        'Revición completa' => 'Revición completa',
                        'Mantenimeinto' => 'Mantenimeinto',

                    ),
                ))
            ->add('descripcion', TextareaType::class, array('required' => false))
            ->add('precio', MoneyType::class)
            ->add('save', SubmitType::class, array('label' => 'Guardar'));
    }
}
