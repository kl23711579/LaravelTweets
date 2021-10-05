<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;

class UserRepository extends BaseRepository {

    public function model()
    {
        // TODO: Implement model() method.
        return "App\\Models\\User";
    }
}

