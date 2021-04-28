<?php


namespace App\Document;


use Doctrine\ODM\MongoDB\Mapping\Annotations\PrePersist;
use Exception;
use Symfony\Component\Validator\Validation;

trait Validator
{
    /**
     * @PrePersist
     * @throws Exception
     */
    public function validate(): void
    {
        $validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
        foreach ($validator->validate($this) as $error) {
            throw new Exception($error->getMessage());
        }
    }
}