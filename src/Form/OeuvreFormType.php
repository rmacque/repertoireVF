<?php

namespace App\Form;

use App\Entity\Oeuvre;
use App\Entity\Categorie;
use App\Entity\Comedien;
use App\Entity\Jouer;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class OeuvreFormType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('photo', FileType::class, [
        'label' => 'Photo',
        'mapped' => false,
        'required' => false,
        'constraints' => [
          new File ([
            'maxSize' => '1024k',
            'mimeTypes' => [
              'application\pdf',
              'application\x-pdf'
            ],
            'mimeTypesMessage' => 'Please upload a valid PDF document'
          ])
        ]
      ])
      ->add('categorie', EntityType::class, [
        'label' => 'Appartenance :',
        'class' => Categorie::class,
        'choice_label' => 'nom',
        'multiple' => false,
        'expanded' => true
      ])
      ->add('nom', TextType::class, [
        'label' => 'Nom :'
      ])
      ->add('parution')
      ->add('listDa', EntityType::class, [
        'label' => 'Direction :',
        'class' => Comedien::class,
        'query_builder' => function (EntityRepository $er){
          return $er->createQueryBuilder('c')
            //->select()
            ->where('c.DA = 1')
            ->orderBy('c.nom');
        },
        'choice_label' => 'nom',
        'multiple' => true,
        'expanded' => false
      ])
      ->add('roles', CollectionType::class, [
        'entry_type' => JouerFormType::class,
        'entry_options' => [
          'label' => false
        ],
        'label' => 'Roles : ',
        'allow_add' => true,
        'allow_delete' => true,
      ])
      /*
      ->add('roles', EntityType::class, [
        'label' => 'Distribution :',
        'class' => Jouer::class,
        'query_builder' => function (EntityRepository $er){
          return $er->createQueryBuilder('j')
          ->where('j.oeuvre = :id')
          ->setParameter('id', 1);
        },
        'choice_label' => 'role',
        'multiple' => true,
        'expanded' => true
      ])
      */
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
      $resolver->setDefaults([
        'data_class' => Oeuvre::class,
      ]);
  }
}
