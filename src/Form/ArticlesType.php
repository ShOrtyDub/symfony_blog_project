<?php

namespace App\Form;

use App\Entity\Articles;
use App\Entity\Categories;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticlesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre',
                'required' => true
            ])
            ->add('auteur', TextType::class, [
                'label' => 'Auteur',
                'required' => true
            ])
            ->add('date')
            ->add('texte', TextType::class, [
                'label' => 'Texte',
                'required' => true
            ])
            ->add('fk_categories', EntityType::class, [
                'class' => Categories::class,
                'choice_label' => 'nom',
                'label' => 'Catégorie',
                'required' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Articles::class,
        ]);
    }
}
