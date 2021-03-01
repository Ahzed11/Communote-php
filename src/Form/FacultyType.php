<?php

namespace App\Form;

use App\Entity\Faculty;
use App\Entity\School;
use App\Repository\SchoolRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FacultyType extends AbstractType
{
    private SchoolRepository $schoolRepository;

    public function __construct(SchoolRepository $schoolRepository)
    {
        $this->schoolRepository = $schoolRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('schools', null, array(
                "class" => School::class,
                "placeholder" => 'Select a faculty',
                "choices" => $this->schoolRepository->findAll(),
            ))
            ->add("submit", SubmitType::class, [
                "attr" => ["class" => "button primary"]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Faculty::class,
        ]);
    }
}
