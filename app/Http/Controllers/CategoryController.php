<?php

namespace App\Http\Controllers;

use App\Http\Requests\CAtegoryRequest;
use App\Http\Requests\CategoryUpdate;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return view('category.index' , compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CAtegoryRequest $request)
    {
        Category::create(['name' => $request->name]);

        return redirect()->route('category.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('category.edit' , compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryUpdate $request)
    {

        $category = Category::findOrFail($request->id);
        $category->update(['name' => $request->name]);

        return redirect('/category');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::findOrFAil($id);
        $category->delete();

        return redirect('/category');
    }
}
