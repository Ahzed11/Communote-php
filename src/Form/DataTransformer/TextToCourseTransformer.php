<?php


namespace App\Form\DataTransformer;


use App\Entity\Course;
use App\Repository\CourseRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class TextToCourseTransformer implements DataTransformerInterface
{
    private CourseRepository $courseRepository;

    public function __construct(CourseRepository $courseRepository) {

        $this->courseRepository = $courseRepository;
    }

    /**
     * @param Course $value
     * @return ?string
     */
    public function transform($value) : ?string
    {
        if ($value === null) {
            return '';
        }

        return (string) $value;
    }

    public function reverseTransform($value) : ?Course
    {
        if (!$value) {
            return null;
        }

        $codeAndTitle = explode("-", str_replace(" ", "", $value));
        if (count($codeAndTitle) < 2) {
            return null;
        }

        $course = $this->courseRepository->getByTitleAndCode($codeAndTitle[0], $codeAndTitle[1]);
        if ($course === null) {
            throw new TransformationFailedException(sprintf(
                'An issue with id "%s" does not exist!',
                $value
            ));
        }

        return $course;
    }
}
