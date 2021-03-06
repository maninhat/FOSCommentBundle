<?php
/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 15.05.14
 * Time: 12:45
 */

namespace FOS\CommentBundle\Validator\Constraints;


use B2B\SiteBundle\Entity\Comments\Vote;
use Doctrine\Common\Util\Debug;
use FOS\CommentBundle\Model\SignedCommentInterface;
use FOS\CommentBundle\Model\SignedVoteInterface;
use FOS\CommentBundle\Model\VoteManagerInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
class VoteUniqueValidator extends ConstraintValidator
{
    /**
     * @var VoteManagerInterface
     */
    private $voteManager;
    /**
     * @var \Symfony\Component\Security\Core\SecurityContextInterface
     */
    private $securityContext;

    function __construct(VoteManagerInterface $voteManagerInterface, SecurityContextInterface $securityContext)
    {

        $this->voteManager = $voteManagerInterface;
        $this->securityContext = $securityContext;
    }


    /**
     * Checks if the passed value is valid.
     *
     * @param Vote $vote The value that should be validated
     * @param \FOS\CommentBundle\Validator\Constraints\VoteUnique|\Symfony\Component\Validator\Constraint $constraint The constraint for the validation
     *
     * @api
     */
    public function validate($vote, Constraint $constraint)
    {
        $user = $this->securityContext->getToken()->getUser();

        if ($vote instanceof SignedVoteInterface
            && $vote->getComment()->getAuthor() == $user
        ) {
            $this->context->addViolation(

                $constraint->message_yourself_comment,
                array(),
                null
            );


        }

        if ($vote->getComment() instanceof SignedCommentInterface) {

            $existingVote = $this->voteManager->findVoteBy(
                array(
                    'comment' => $vote->getComment(),
                    'voter' => $user,
                )
            );

            if ($existingVote) {
                $this->context->addViolation(

                    $constraint->message_unique_comment,
                    array(),
                    null
                );


            }
        }
    }
}