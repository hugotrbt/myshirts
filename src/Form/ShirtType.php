<?php

namespace App\Form;

use App\Entity\Shirt;
use App\Entity\Stadium;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ShirtType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('team')
            ->add('lockerRoom', null, [
                'disabled' => true,
            ])
            ->add('stadia', EntityType::class, [
                'class' => Stadium::class,
                'choice_label' => 'description',
                'multiple' => true,
            ])
            ->add('imageName', TextType::class,  ['disabled' => true])
            ->add('imageFile', VichImageType::class, ['required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Shirt::class,
        ]);
    }
}
