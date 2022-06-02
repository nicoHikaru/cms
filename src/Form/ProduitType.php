<?php

namespace App\Form;

use App\Entity\Produits;
use App\Entity\TypeProduits;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class,[
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Saisir nom article',
                    ]),
                ],
                'attr' => array(
                    'placeholder' => 'Saisir...',
                )
            ])
            ->add('price',TextType::class,[
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Saisir prix article',
                    ]),
                ],
                'attr' => array(
                    'placeholder' => 'Saisir...',
                )
            ])
            ->add('reduction',TextType::class,[
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Saisir...',
                )
            ])
            ->add('photo',FileType::class,[
                'label' => 'photo article',
                'constraints' => [
                    new Image([
                        'maxSize' => '200k'
                    ])
                    ],
                    'required' => false,
            ])
            ->add('typeProduit',EntityType::class,[
                'required' => false,
                'class' => TypeProduits::class,
                'query_builder' => function (EntityRepository $typeProduits) {
                    return $typeProduits->createQueryBuilder('t')
                        ->orderBy('t.nom', 'ASC');
                },
                'choice_label' => 'nom',
                'placeholder' => 'choisir',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produits::class,
        ]);
    }
}
