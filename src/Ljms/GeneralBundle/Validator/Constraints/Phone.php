<?php
namespace Ljms\GeneralBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Phone extends Constraint
{
    public $message = 'Incorrect phone "%string%".';
}