<?php

namespace App\Services;

use App\Interfaces\CategoryRepositoryInterface;
use Illuminate\Support\Facades\Validator;
use Exception;

class CategoryService
{
    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getAllCategories() { return $this->categoryRepository->all(); }
    public function findCategory($id) { return $this->categoryRepository->find($id); }

    public function createCategory(array $data)
    {
        $validator = Validator::make($data, ['name' => 'required|string|max:255|unique:categories,name']);
        if ($validator->fails()) { throw new Exception($validator->errors()->first()); }
        return $this->categoryRepository->create($data);
    }

    public function updateCategory($id, array $data)
    {
        $validator = Validator::make($data, ['name' => 'required|string|max:255|unique:categories,name,'.$id]);
        if ($validator->fails()) { throw new Exception($validator->errors()->first()); }
        return $this->categoryRepository->update($id, $data);
    }

    public function deleteCategory($id) { $this->categoryRepository->delete($id); }
}