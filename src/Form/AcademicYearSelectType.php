<?php

namespace App\Form;

use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AcademicYearSelectType extends AbstractType
{

    public function getParent(): string
    {
        return ChoiceType::class;
    }

    public function configureOptions(OptionsResolver $resolver) : void
    {
        $currentYear = date("Y");
        $options = [];
        foreach (range($currentYear - 8, $currentYear) as $year)
        {
            $yearDateTime = DateTime::createFromFormat('d/m/Y', '15/09/'.$year);
            $options[$year . " - " . $year + 1] = $yearDateTime;
        }

        $resolver->setDefaults([
            'choices' => $options,
            'placeholder' => 'Choose an academic year',
        ]);
    }
}
