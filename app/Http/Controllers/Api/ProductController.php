<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProductController extends Controller
{
    /**
     * get all account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = QueryBuilder::for(Product::class)
            // ->defaultSort('-created_at')
            ->allowedIncludes([
                'category',
                'supplier'
            ])
            ->allowedFilters([
                'title',
            ])
            ->allowedSorts('title', 'created_at', 'id')
            ->paginate($request->get('per_page', 15));
            
        return ProductResource::collection($data);
    }
}
