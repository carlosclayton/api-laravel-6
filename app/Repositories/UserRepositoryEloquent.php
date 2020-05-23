<?php

namespace App\Repositories;

use App\Presenters\UserPresenter;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\UserRepository;
use App\Models\User;
use App\Validators\UserValidator;

/**
 * Class UserRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class UserRepositoryEloquent extends BaseRepository implements UserRepository
{
    protected $fieldSearchable = [
        'id' => '=',
        'name' => 'like',
        'email' => 'like',
        'role' => '='
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }

    public function onlyTrashed()
    {
        $this->model = $this->model->onlyTrashed();
        return $this;
    }
    public function restore($id)
    {
        return $this->model->onlyTrashed()->find($id)->restore();
    }

    /**
     * Specify Validator class name
     *
     * @return mixed
     */
    public function validator()
    {

        return UserValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function presenter()
    {
        return new UserPresenter();
    }

    public function paginate($limit = null, $page = null, $columns = ['*'], $method = "paginate")
    {
        $this->applyCriteria();
        $this->applyScope();
        $limit = is_null($limit) ? config('repository.pagination.limit', 15) : $limit;
        $results = $this->model->{$method}($limit, $columns, 'page', $page);
        $results->appends(app('request')->query());
        $this->resetModel();
        return $this->parserResult($results);
    }

}
