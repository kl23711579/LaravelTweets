<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class TimelineCriteriaCriteria.
 *
 * @package namespace App\Criteria;
 */
class TimelineCriteriaCriteria implements CriteriaInterface
{
    protected $followings;

    public function __construct($followings)
    {
        $this->followings = $followings;
    }
    /**
     * Apply criteria in query repository
     *
     * @param string              $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $model = $model->whereIn('user_id', $this->followings)
            ->orWhere('user_id', auth()->user()->id)
            ->latest('published_at');

        return $model;
    }
}
