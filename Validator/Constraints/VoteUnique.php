<?php
/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 15.05.14
 * Time: 12:45
 */

namespace FOS\CommentBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;
/**
 * @Annotation
 */

class VoteUnique extends Constraint{
    public  $message_unique_comment='already.commented';
    public  $message_yourself_comment='once.voted';


    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }


    public function validatedBy()
    {
        return 'fos_comment.validator.unique.voter';
    }
}