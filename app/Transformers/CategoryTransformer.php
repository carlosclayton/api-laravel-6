<?php

namespace App\Transformers;

use Carbon\Carbon;
use League\Fractal\TransformerAbstract;
use App\Models\Category;

/**
 * Class CategoryTransformer.
 *
 * @package namespace App\Transformers;
 */
class CategoryTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['products'];

    /**
     * Transform the Category entity.
     *
     * @param \App\Models\Category $model
     *
     * @return array
     */
    public function transform(Category $model)
    {
        return [
            'id'
            => (int)$model->id,
            'name' => $model->name,
            'description' => $model->description,
            'created_at' => Carbon::parse($model->created_at)->format('d/m/Y H:i:s'),
            'updated_at' => Carbon::parse($model->updated_at)->format('d/m/Y H:i:s'),
            'deleted_at' => ($model->deleted_at == null) ? null : Carbon::parse($model->deleted_at)->format('d/m/Y H:i:s'),
        ];
    }

    public function includeProducts(Category $model)
    {
        return $this->collection($model->products, new ProductTransformer());
    }

}
