<?php

namespace App\Repositories;

use App\Interfaces\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Support\Str;
                    
class CategoryRepository implements CategoryRepositoryInterface
{
    public function all()
    {
        return Category::all();
    }

    public function find($id)
    {
        return Category::findOrFail($id);
    }

    public function create(array $data)
    {
        $data['slug'] = Str::slug($data['name']);
        return Category::create($data);
    }

    public function update($id, array $data)
    {
        $category = $this->find($id);
        $data['slug'] = Str::slug($data['name']);
        $category->update($data);
        return $category;
    }

    public function delete($id)
    {
        $this->find($id)->delete();
    }
}