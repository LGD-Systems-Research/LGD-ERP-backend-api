<?php
/**
 * Created by PhpStorm.
 * User: darryldecode
 * Date: 4/28/2018
 * Time: 12:54 AM
 */

namespace App\Components\Announcement\Repositories;


use App\Components\Announcement\Models\Announcement;
use App\Components\Core\BaseRepository;

class AnnouncementRepository extends BaseRepository
{
    public function __construct(Announcement $model)
    {
        parent::__construct($model);
    }

    /**
     * @param array $params
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAnnouncements(array $params = [])
    {
        $q = $this->model
            ->with(array_merge(['author'],$params['with'] ?? []))
            ->orderBy($params['order_by'] ?? 'id',$params['order_sort'] ?? 'desc');

        return $q->paginate($params['per_page'] ?? 10);
    }

    /**
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id,array $data)
    {
        $model = $this->model->find($id);

        if(!$model) return false;

        return $model->update($data);
    }

    /**
     * @param int $id
     *
     * @return bool
     * @throws \Exception
     */
    public function delete(int $id)
    {
        $model = $this->model->find($id);

        if(!$model) return false;

        $model->delete();

        return true;
    }
}