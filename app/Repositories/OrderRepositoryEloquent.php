<?php

namespace App\Repositories;

use App\Presenters\OrderPresenter;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\OrderRepository;
use App\Models\Order;
use App\Validators\OrderValidator;

/**
 * Class OrderRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class OrderRepositoryEloquent extends BaseRepository implements OrderRepository
{
    protected $fieldSearchable = [
        'id' => '=',
        'status' => '=',
        'product.price' => 'like'
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Order::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return OrderValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }


    /**
     * @param null $limit
     * @param null $page
     * @param string[] $columns
     * @param string $method
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection|mixed
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function paginate($limit = null, $page = null, $columns =
    ['*'], $method = "paginate")
    {
        $this->applyCriteria();
        $this->applyScope();
        $limit = is_null($limit) ?
            config('repository.pagination.limit', 15) : $limit;
        $results = $this->model->{$method}($limit, $columns, 'page',
            $page);
        $results->appends(app('request')->query());
        $this->resetModel();
        return $this->parserResult($results);
    }

    /**
     * @return OrderPresenter|string|null
     */
    public function presenter()
    {
        return new OrderPresenter();
    }

}
