<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\SubcategoryResource;
use App\Models\Admin\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('Status', 1)->latest()->get();
        return CategoryResource::collection($categories);
    }
    public function getSubCategories($category_id)
    {
        $subcategories = Subcategory::where('category_id', $category_id)->where('Status',1)->get();
        return SubcategoryResource::collection($subcategories);
    }
}
