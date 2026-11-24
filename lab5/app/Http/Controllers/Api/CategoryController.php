<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreCategoryRequest;
use App\Http\Requests\Api\UpdateCategoryRequest;
use App\Http\Resources\Api\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryService $categories
    ) {}

    public function index(): AnonymousResourceCollection
    {
        return CategoryResource::collection($this->categories->paginate());
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = $this->categories->create($request->validated());

        return (new CategoryResource($category))
            ->response()
            ->setStatusCode(201)
            ->header('Location', route('api.categories.show', $category));
    }

    public function show(Category $category): CategoryResource
    {
        return new CategoryResource($category);
    }

    public function update(UpdateCategoryRequest $request, Category $category): CategoryResource
    {
        $updated = $this->categories->update($category, $request->validated());

        return new CategoryResource($updated);
    }

    public function destroy(Category $category): Response
    {
        $this->categories->delete($category);

        return response()->noContent();
    }
}
