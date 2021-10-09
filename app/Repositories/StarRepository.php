<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;

class StarRepository extends BaseRepository {

    public function model()
    {
        // TODO: Implement model() method.
        return "App\\Models\\Star";
    }
}
