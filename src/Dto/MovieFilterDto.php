<?php

namespace App\Dto;

use App\Entity\Movie;

class MovieFilterDto
{
    public ?string $search = null;

    public function isEmpty():bool
    {
        return $this->search === null;
    }

}