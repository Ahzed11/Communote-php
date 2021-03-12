<?php

namespace App\Form;

use App\Entity\Course;
use App\Entity\Faculty;
use App\Entity\Note;
use App\Repository\CourseRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotNull;

class NoteType extends AbstractType
{
    private CourseRepository $courseRepository;

    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

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
            ->add('title')
            ->add('shortDescription')
            ->add('description')
            ->add('course', null, array(
                "class" => Course::class,
                "placeholder" => 'Select a course',
                "choices" => $this->courseRepository->findAll(),
            ))
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
