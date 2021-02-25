<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("email", EmailType::class, [
                "attr" => [
                    "placeholder" => "firstname.lastname@student.school.com",
                ],
            ])
            ->add("firstName", TextType::class, [
                "attr" => [
                    "placeholder" => "First name",
                ],
            ])
            ->add("lastName", TextType::class, [
                "attr" => [
                    "placeholder" => "Last name",
                ],
            ])
            ->add("plainPassword", RepeatedType::class, [
                "type" => PasswordType::class,
                "invalid_message" => "The password fields must match.",
                "required" => true,
                "first_options"  => [
                    "label" => "Password",
                    "attr" => [
                        "placeholder" => "********",
                    ],
                ],
                "second_options" => [
                    "label" => "Confirmation",
                    "attr" => [
                        "placeholder" => "********",
                    ],
                ],
                "constraints" => [
                    new NotBlank([
                    ]),
                    new Length([
                        "min" => 8,
                        "max" => 255
                    ]),
                ],
            ])
            ->add("agreeToTerms", CheckboxType::class, [
                "mapped" => false,
                "constraints" => new IsTrue(),
            ])
            ->add("submit", SubmitType::class, [
                "attr" => ["class" => "button primary"]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => User::class,
        ]);
    }
}
