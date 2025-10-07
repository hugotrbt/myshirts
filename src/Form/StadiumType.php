<?php

namespace App\Form;

use App\Entity\Shirt;
use App\Entity\Stadium;
use App\Entity\Member;
use App\Repository\ShirtRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StadiumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        dump($options);

        // Pour obtenir le shirt actuel dans les données du formulaire
        $stadium = $options['data'] ?? null;

        // Récupération du membre lié au stade
        $member = $stadium->getCreator();

        $builder
            ->add('description', TextType::class, [
            'label' => 'Description',
            'required' => true,
            ])
            ->add('published', CheckboxType::class, [
            'label' => 'Published',
            'required' => false,
            ])
            ->add('creator', null, [
                'label' => 'Creator',
                'choice_label' => 'email',
                'disabled'   => true,
            ])
            ->add('shirts', EntityType::class, [
                'class' => Shirt::class,
                'by_reference' => false,
                'multiple' => true,
                'expanded' => true,
                // Chargement des shirts de la lockerRoom du membre courant
                'query_builder' => function (ShirtRepository $er) use ($member) {
                                    return $er->createQueryBuilder('o')
                                            ->leftJoin('o.lockerRoom', 'i')
                                            ->leftJoin('i.member', 'm')
                                            ->andWhere('m.id = :memberId')
                                            ->setParameter('memberId', $member->getId())
                                            ;
                                    }
                            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Stadium::class,
        ]);
    }
}
