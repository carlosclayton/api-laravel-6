<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;



/**
 * Interface CategoryRepository.
 *
 * @package namespace App\Repositories;
 */
interface CategoryRepository extends RepositoryInterface
{
    public function paginate($limit = null, $page = null, $columns = ['*'], $method = "paginate");
}
