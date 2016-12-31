<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class MotoForm extends AbstractType
{
    // Formulario para el modelo Moto, con este podemos verificar 
    // que los datos sean los correctos cuando se envian a rregistrar

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('placa', TextType::class)
            ->add('cliente_dni', TextType::class)
            ->add('color', ChoiceType::class, array(
                    'choices'  => array(
                        'Negro' => 'Negro',
                        'Rojo' => 'Rojo',
                        'Amarillo' => 'Amarillo',
                        'Verde' => 'Verde',
                        'Azul' => 'Azul',
                        'Naranja' => 'Naranja',
                        'Blanco' => 'Blanco',
                    ),
                ))
            ->add('marca', TextType::class)
            ->add('descripcion', TextareaType::class, array('required' => false))
            ->add('save', SubmitType::class, array('label' => 'Guardar'));
    }
}
