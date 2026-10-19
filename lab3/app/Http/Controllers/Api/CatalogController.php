<?php

namespace App\Http\Controllers\Api;

use App\Data\CatalogFilterData;
use App\Http\Controllers\Controller;
use App\Http\Requests\CatalogFilterRequest;
use App\Http\Resources\ProductResource;
use App\Services\CatalogService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CatalogController extends Controller
{
    public function __construct(
        protected CatalogService $catalogService
    ) {}

    public function index(CatalogFilterRequest $request): AnonymousResourceCollection
    {
        $filters = CatalogFilterData::fromArray($request->validated());

        return ProductResource::collection($this->catalogService->paginate($filters));
    }

    public function show(string $slug): ProductResource
    {
        return new ProductResource($this->catalogService->findBySlug($slug));
    }
}
