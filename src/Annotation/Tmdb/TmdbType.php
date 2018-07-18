<?php

namespace App\Annotation\Tmdb;

use Doctrine\ORM\Mapping\Annotation;
use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\Common\Annotations\Annotation\Enum;

/**
 * Annotation used when linking object with TMDB. It allows to indicate type of data, the annotated class will be matching (person, movie or tv).
 * This annotation allows to use appropriate URL when requesting TMDB to build instance of annotated class.
 * @Annotation
 * @Target("CLASS")
 */
class TmdbType
{
    /**
     * @Enum({"MOVIE", "TV", "PERSON"})
     */
    public $type;
}