<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;

class UserRepository extends BaseRepository {

    public function model()
    {
        // TODO: Implement model() method.
        return "App\\Models\\User";
    }

    /**
    * Get following users by id
    *
    * @param       $id
    *
    * @return mixed
    */
    public function getFollowingUsers($id)
    {
        $followingUsers = $this->model
                                ->find($id)
                                ->follows();

        return $followingUsers;
    }
}

