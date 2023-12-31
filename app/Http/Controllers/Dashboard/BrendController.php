<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\BrendStoreRequest;
use App\Http\Requests\BrendUpdateRequest;
use App\Models\Brend;
use App\Models\Product;
use App\Services\BrendService;
use Illuminate\Http\Request;

class BrendController extends BaseController
{
    private $productController;
    public function __construct(ProductController $productController)
    {
        $this->productController = $productController;
    }
    public function index()
    {
        $brends = Brend::orderBy('id', 'desc')->get();
        return view('dashboard.brend.crud', [
            'brends'=>$brends
        ]);
    }

    public function store(BrendStoreRequest $request)
    {
        $result = (new BrendService())->store($request->validated());
        if($result['status']){
            return redirect()->route('dashboard.brend.index')->with('success', $result['message']);
        }
        return redirect()->route('dashboard.brend.index')->with('error', $result['message']);
    }

    public function update(BrendUpdateRequest $request, $id)
    {
        $result = (new BrendService())->update($request->validated(), $id);
        if($result['status']){
            return redirect()->route('dashboard.brend.index')->with('success', $result['message']);
        }
        return redirect()->route('dashboard.brend.index')->with('error', $result['message']);
    }

    public function destroy($id)
    {
        $this->fileDelete('\Brend', $id, 'photo');
        Brend::find($id)->delete();
        foreach (Product::where('brend_id', $id)->get() as $prod) {
            $this->productController->destroy($prod->id);
        }
        return back()->with('success', 'Data deleted.');
    }
}
