<?php

namespace App\Form;

use App\Entity\Faculty;
use App\Entity\Study;
use App\Repository\FacultyRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StudyType extends AbstractType
{
    private FacultyRepository $facultyRepository;

    public function __construct(FacultyRepository $facultyRepository)
    {
        $this->facultyRepository = $facultyRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('faculty', null, array(
                "class" => Faculty::class,
                "placeholder" => 'Select a faculty',
                "choices" => $this->facultyRepository->findAll(),
            ))
            ->add("submit", SubmitType::class, [
                "attr" => ["class" => "button primary"]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Study::class,
        ]);
    }
}
