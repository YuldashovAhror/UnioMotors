<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreRequest;
use App\Http\Requests\Category\UpdateRequest;
use App\Models\Brend;
use App\Models\Category;
use App\Models\Product;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    private $productController;
    public function __construct(ProductController $productController)
    {
        $this->productController = $productController;
    }
    public function index()
    {
        $categories = Category::orderBy('id', 'desc')->get();
        // $brends = Brend::orderBy('id', 'desc')->get();
        return view('dashboard.category.crud', [
            'categories'=>$categories,
            // 'brends'=>$brends,
        ]);
    }

    public function store(StoreRequest $request)
    {
        $result = (new CategoryService())->store($request->validated());
        if($result['status']){
            return redirect()->route('dashboard.category.index')->with('success', $result['message']);
        }
        return redirect()->route('dashboard.category.index')->with('error', $result['message']);
    }

    public function update(UpdateRequest $request, $id)
    {
        $result = (new CategoryService())->update($request->validated(), $id);
        if($result['status']){
            return redirect()->route('dashboard.category.index')->with('success', $result['message']);
        }
        return redirect()->route('dashboard.category.index')->with('error', $result['message']);
    }

    public function destroy($id)
    {
        $this->fileDelete('\Category', $id, 'photo');
        Category::find($id)->delete();
        foreach (Product::where('category_id', $id)->get() as $prod) {
            $this->productController->destroy($prod->id);
        }
        return back()->with('success', 'Data deleted.');
    }
}
