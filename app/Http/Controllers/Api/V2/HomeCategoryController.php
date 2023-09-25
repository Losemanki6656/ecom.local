<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\CategoryCollection;
use App\Http\Resources\V2\HomeCategoryCollection;
use App\Models\Category;
use App\Models\HomeCategory;

class HomeCategoryController extends Controller
{
    public function index()
    {
        return new CategoryCollection(Category::all());
    }
}
