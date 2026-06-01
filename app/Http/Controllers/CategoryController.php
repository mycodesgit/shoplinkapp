<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.category.listcat');
    }

    public function store(Request $request) 
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'catname' => 'required',
            ]);

            $categoryName = $request->input('catname'); 
            $subcategory = $request->input('subcategory');
            $existingCategory = Category::where('catname', $categoryName)->where('subcategory', $subcategory)->first();

            if ($existingCategory) {
                return response()->json(['error' => true, 'message' => 'Category already exists!'],  404);
            }

            try {
                Category::create([
                    'catname' => $request->input('catname'),
                    'subcategory' => $request->input('subcategory'),
                    'caticon' => $request->input('caticon'),
                    'posted' => Auth::guard('web')->user()->fname .' ' . Auth::guard('web')->user()->lname,
                ]);
                return response()->json(['success' => true, 'message' => 'Category stored successfully!'],  200);

            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to store Category!'],  404);
            }
        }
    }

    public function show() 
    {
        $data = Category::where('pcstatus', '!=', 3)->orderBy('catname', 'ASC')->get();

        return response()->json(['data' => $data]);
    }

    public function update(Request $request) 
    {
        $request->validate([
            'id' => 'required',
            'catname' => 'required',
        ]);

        try {
            $categoryName = $request->input('catname');
            $subcategory = $request->input('subcategory');
            $existingCategory = Category::where('catname', $categoryName)->where('subcategory', $subcategory)->where('id', '!=', $request->input('id'))->first();

            if ($existingCategory) {
                return response()->json(['error' => true, 'message' => 'Category already exists!'], 200);
            }

            $categoryIcon = $request->input('caticon');
            if (!str_starts_with($categoryIcon, 'ti ')) {
                $categoryIcon = 'ti ' . $categoryIcon;
            }

            $category = Category::findOrFail($request->input('id'));
            $category->update([
                'catname' => $categoryName,
                'subcategory' => $subcategory,
                'pcstatus' => $request->input('pcstatus'),
                'caticon' => $categoryIcon,
                'posted' => Auth::guard('web')->user()->fname .' ' . Auth::guard('web')->user()->lname,
            ]);
            return response()->json(['success' => true, 'message' => 'Updated Successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to update Category!'], 404);
        }
    }

    public function destroy($id) 
    {
        $category = Category::find($id);
        if ($category) {
            $category->pcstatus = 3;
            $category->save();
            return response()->json(['success'=> true, 'message'=>'Deleted successfully']);
        }
        return response()->json(['error'=> true, 'message'=>'Category not found']);
    }
}
