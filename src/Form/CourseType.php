<?php

namespace App\Form;

use App\Entity\Course;
use App\Entity\Study;
use App\Entity\Year;
use App\Repository\StudyRepository;
use App\Repository\YearRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseType extends AbstractType
{
    private StudyRepository $studyRepository;
    private YearRepository $yearRepository;

    public function __construct(StudyRepository $studyRepository, YearRepository $yearRepository)
    {
        $this->studyRepository = $studyRepository;
        $this->yearRepository = $yearRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('code')
            ->add('study', null, array(
                "class" => Study::class,
                "placeholder" => 'Select a study',
                "choices" => $this->studyRepository->findAll(),
            ))
            ->add('year', null, array(
                "class" => Year::class,
                "placeholder" => 'Select a year',
                "choices" => $this->yearRepository->findAll(),
            ))
            ->add("submit", SubmitType::class, [
                "attr" => ["class" => "button primary w-full"]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Course::class,
        ]);
    }
}
