<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormulaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre de l’article',
                'attr' => ['placeholder' => 'Entrez le titre'],
            ])
            ->add('texte', TextareaType::class, [
                'label' => 'Contenu',
                'attr' => ['placeholder' => 'Entrez le contenu de l’article', 'rows' => 6],
            ])
            ->add('publie', null, [
                'label' => 'Publié',
            ])
            ->add('image', FileType::class, [
                'label' => 'Image de l’article (PNG, JPG)',
                'mapped' => false, // Pas directement lié à l'entité
                'required' => false, // Pas obligatoire
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}