<?php

namespace App\Http\Controllers\Api\Blog\Admin;

// use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Support\Str;
// use Illuminate\Http\Request;
use App\Http\Requests\BlogCategoryUpdateRequest;
use App\Http\Requests\BlogCategoryCreateRequest;
use App\Repositories\BlogCategoryRepository;
use App\Http\Resources\Api\Blog\Admin\CategoryResource;

class CategoryController extends BaseController
{
    public function __construct(private BlogCategoryRepository $blogCategoryRepository)
    {
        parent::__construct();
    }

    public function index()
    {
        $categories = \App\Models\BlogCategory::paginate(15);
        return CategoryResource::collection($categories);
    }

    public function show(string $id)
    {
        $item = $this->blogCategoryRepository->getEdit($id);
        if (empty($item)) {
            return ['message' => `Запис id=[{$id}] не знайдено`];
        }
        return new CategoryResource($item);
    }

    public function store(BlogCategoryCreateRequest $request)
    {
        $data = $request->input();

        $item = (new BlogCategory())->create($data);

        if ($item) {
            return [
                'success' => true,
                'message' => 'Успішно збережено'
            ];
        } else {
            return ['message' => 'Помилка збереження'];
        }
    }

    public function update(BlogCategoryUpdateRequest $request, string $id)
    {
        $item = $this->blogCategoryRepository->getEdit($id);
        if (empty($item)) {
            return ['message' => `Запис id=[{$id}] не знайдено`];
        }

        $data = $request->all();

        $result = $item->update($data);

        if ($result) {
            return [
                'success' => true,
                'message' => 'Успішно збережено'
            ];
        } else {
            return ['message' => 'Помилка збереження'];
        }
    }

    public function destroy(string $id)
    {
        $item = $this->blogCategoryRepository->getEdit($id);

        if (empty($item)) {
            return ['message' => `Запис id=[{$id}] не знайдено`];
        }

        $item->delete();

        return ['success' => true, 'message' => `Запис id=[$id] успішно видалено`];
    }
}
