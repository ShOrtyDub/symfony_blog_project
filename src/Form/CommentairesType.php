<?php

namespace App\Form;

use App\Entity\Commentaires;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CommentairesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('auteur', TextType::class, [
            'label' => 'Texte',
            'required' => true
        ])
        ->add('date_heure')
        ->add('texte', TextType::class, [
            'label' => 'Texte',
            'required' => true
        ])
        ->add('commentaire', TextType::class, [
            'label' => 'Texte',
            'required' => true
        ])
        ->add('status');

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaires::class,
        ]);
    }
}
