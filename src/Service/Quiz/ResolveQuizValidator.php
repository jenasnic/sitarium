<?php

namespace App\Service\Quiz;

use App\Entity\Quiz\Quiz;

/**
 * Allows to validate quiz resolution, i.e. check if all responses have been found for quiz.
 */
class ResolveQuizValidator
{
    /**
     * @param array<string> $responses
     */
    public function validate(Quiz $quiz, array $responses): bool
    {
        if (count($responses) != count($quiz->getResponses())) {
            return false;
        }

        foreach ($quiz->getResponses() as $response) {
            if (!in_array($response->getTitle(), $responses)) {
                return false;
            }
        }

        return true;
    }
}
