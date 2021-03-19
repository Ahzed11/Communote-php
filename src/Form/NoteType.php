<?php

namespace App\Form;

use App\Entity\Note;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotNull;

class NoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Note|Null $note */
        $note = $options['data'] ?? null;
        $isEdit = $note && $note->getId();

        $fileConstraints = [
            new File([
                'maxSize' => '50Mi',
                'mimeTypes' => [
                    'application/pdf',
                    'application/x-pdf',
                    'image/jpeg',
                    'image/png'
                ]
            ])
        ];

        if (!$isEdit || !$note) {
            $fileConstraints[] = new NotNull([
                'message' => 'No file has been uploaded.'
            ]);
        }

        $builder
            ->add('title', null, [
                'attr' => [
                    'placeholder' => 'Title'
                ]
            ])
            ->add('shortDescription', null, [
                'attr' => [
                    'placeholder' => 'A short text to describe my note. It will be displayed on the note card'
                ]
            ])
            ->add('description', null, [
                'attr' => [
                    'placeholder' => 'A longer text to describe in detail your note. It will be displayed on the note view page'
                ]
            ])
            ->add('course', CourseSelectTextType::class, [
                'attr' => [
                    'placeholder' => 'CODE - Course name'
                ]
            ])
            ->add('noteFile', FileType::class, array(
                    'mapped' => false,
                    'required' => !$isEdit || !$note,
                    'constraints' => $fileConstraints
                )
            )
            ->add("submit", SubmitType::class, [
                "attr" => ["class" => "button primary w-full"]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Note::class,
        ]);
    }
}
