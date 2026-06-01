<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Models\Category;
use App\Models\Product;

class ProductsController extends Controller
{
    public function index()
    {
        $categories = Category::where('pcstatus', '=', '1')->get();

        return view('admin.items.products', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'catid'  => 'required|integer',
            'subcatid'  => 'required|integer',
            'prdctname'   => 'required|string|max:255',
            'prdctdesc'   => 'required|string',
            'prdctsku'   => 'required|string|max:100',
            'prdctprice'  => 'required|numeric',
            'prdctstock'  => 'required|integer',
            'prdctimage.*'  => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        try {
            $paths = [];

            foreach ($request->file('prdctimage') as $image) {
                $paths[] = $image->store('products', 'public');
            }

            Product::create([
                'catid'       => $request->catid,
                'subcatid'    => $request->subcatid,
                'prdctname'   => $request->prdctname,
                'prdctdesc'   => $request->prdctdesc,
                'prdctsku'   => $request->prdctsku,
                'prdctprice'  => $request->prdctprice,
                'prdctstock'  => $request->prdctstock,
                'prdctimage'  => json_encode($paths),
                'prdctvariation' => $request->prdctvariation,
                'prdcttag' => $request->prdcttag,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Product uploaded successfully!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Upload failed: ' . $e->getMessage(),
            ]);
        }
    }

    public function show()
    {
        $data = Product::join('category', 'products.catid', '=', 'category.id')
                ->where('pstatus', '!=', '3')
                ->select('products.*', 'category.catname')
                ->get();
                
        return response()->json(['data' => $data]);
    }

    public function update(Request $request) 
    {
        $user = Product::find($request->id);
        
        $request->validate([
            'id' => 'required',
            'catid'  => 'required|integer',
            'subcatid'  => 'required|integer',
            'prdctname'   => 'required|string|max:255',
            'prdctdesc'   => 'required|string',
            'prdctcode'   => 'required|string|max:100',
            'prdctprice'  => 'required|numeric',
            'prdctstock'  => 'required|integer',
            'prdctimage'   => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        try {
            $prdct = Product::findOrFail($request->input('id'));

            if ($request->hasFile('prdctimage')) {
                if ($prdct->prdctimage && Storage::disk('public')->exists(str_replace('storage/', '', $prdct->prdctimage))) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $prdct->prdctimage));
                }


                $path = $request->file('prdctimage')->store('products', 'public');
                $prdct->prdctimage = $path;
            }

            $prdct->update([
                'catid' => $request->catid,
                'subcatid' => $request->subcatid,
                'prdctname'  => $request->prdctname,
                'prdctdesc'  => $request->prdctdesc,
                'prdctcode'  => $request->prdctcode,
                'prdctprice' => $request->prdctprice,
                'prdctstock' => $request->prdctstock,
                'prdctimage' => $prdct->prdctimage,
            ]);

            return response()->json(['success' => true, 'message' => 'User Password updated successfully!']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to update User Password!']);
        }
    }

    public function destroy($id) {
        $prdct = Product::find($id);
        if ($prdct) {
            $prdct->pstatus = 3;
            $prdct->save();
            return response()->json(['success'=> true, 'message'=>'Product deleted successfully']);
        }
        return response()->json(['error'=> true, 'message'=>'Product not found']);
    }
}
