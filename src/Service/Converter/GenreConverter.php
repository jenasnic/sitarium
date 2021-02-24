<?php

namespace App\Service\Converter;

use App\Entity\Tagline\Genre;
use App\Model\Tmdb\Genre as TmdbGenre;
use Symfony\Component\String\Slugger\SluggerInterface;

class GenreConverter
{
    protected SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function convert(TmdbGenre $genre): Genre
    {
        $entity = new Genre();

        $entity->setTmdbId($genre->getId());
        $entity->setName($genre->getName());
        $entity->setSlug($this->slugger->slug($genre->getName()));

        return $entity;
    }
}
