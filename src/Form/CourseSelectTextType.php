<?php

namespace App\Form;

use App\Form\DataTransformer\TextToCourseTransformer;
use App\Repository\CourseRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseSelectTextType extends AbstractType
{
    private CourseRepository $courseRepository;

    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder->addModelTransformer(new TextToCourseTransformer($this->courseRepository));
    }

    public function getParent(): string
    {
        return TextType::class;
    }

    public function configureOptions(OptionsResolver $resolver) : void
    {
        $resolver->setDefaults([
            'invalid_message' => 'No classes match',
        ]);
    }
}
