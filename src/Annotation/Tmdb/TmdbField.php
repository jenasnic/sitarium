<?php

namespace App\Annotation\Tmdb;

use Doctrine\ORM\Mapping\Annotation;
use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\Common\Annotations\Annotation\Required;

/**
 * Annotation used to define mapping between properties of PHP object and properties in TMDB response (JSon flux).
 * This annotation is used on fields of object we want to link with TMDB and allows to indicate the name of matching property in TMDB and type of data.
 * @Annotation
 * @Target("PROPERTY")
 */
class TmdbField
{
    /**
     * Name of property in TMDB response.
     * @Required
     * @var string
     */
    public $name;

    /**
     * Type of value for cast (if not specified, property will be set as defined in JSon flux => string or JSon array).
     * NOTE 1 : For date, specify date format (using dateFormat property).
     * NOTE 2 : For object or array, specify class to use to build sub entities (using subClass property).
     * @Enum({"string", "integer", "float", "datetime", "object", "array"})
     * @var string
     */
    public $type;

    /**
     * For type 'datetime' only => allows to specify date format to use when parsing string to set date as Datetime value.
     * @var string
     */
    public $dateFormat;

    /**
     * For type 'object' or 'array' only => allows to specify class used to build sub entities when building sub entity or filling array.
     * @var string
     */
    public $subClass;
}
