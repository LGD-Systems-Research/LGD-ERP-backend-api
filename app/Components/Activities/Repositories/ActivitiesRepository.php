<?php


namespace App\Components\Activities\Repositories;


use App\Components\Activities\Models\Activity;
use App\Components\Core\BaseRepository;

class ActivitiesRepository extends BaseRepository
{
    public function __construct(Activity $model)
    {
        parent::__construct($model);
    }
}