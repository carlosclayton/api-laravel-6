<?php

namespace App\Transformers;

use Carbon\Carbon;
use League\Fractal\TransformerAbstract;
use App\Models\Product;

/**
 * Class ProductTransformer.
 *
 * @package namespace App\Transformers;
 */
class ProductTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['category', 'orders'];

    /**
     * Transform the Product entity.
     *
     * @param \App\Models\Product $model
     *
     * @return array
     */
    public function transform(Product $model)
    {
        return [
            'id' => (int)$model->id,
            'name' => $model->name,
            'price' => $model->price,
            'description' => $model->description,
            'created_at' => Carbon::parse($model->created_at)->format('d/m/Y H:i:s'),
            'updated_at' => Carbon::parse($model->updated_at)->format('d/m/Y H:i:s'),
            'deleted_at' => ($model->deleted_at == null) ? null :
                Carbon::parse($model->deleted_at)->format('d/m/Y H:i:s'),
        ];
    }

    public function includeCategory(Product $model)
    {
        return $this->item($model->category, new CategoryTransformer());
    }

    public function includeOrders(Product $model)
    {
        return $this->collection($model->orders, new
        OrderTransformer());
    }

}
