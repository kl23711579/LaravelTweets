<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;

class PostRepository extends BaseRepository {

    public function model()
    {
        // TODO: Implement model() method.
        return "App\\Models\\Post";
    }
}

