<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Models\WelcomeBanner;

class BannerController extends Controller
{
    public function index()
    {
        return view('admin.banner.welcomebanner');
    }

    public function store(Request $request)
    {
        $request->validate([
            'welcometext'   => 'required|string|max:255',
            'welcomesubtext'   => 'required|string',
            'welcomestatus'  => 'required|in:active,inactive,Active,Inactive',
        ]);

        try {
            $status = ucfirst(strtolower($request->welcomestatus));

            if ($status === 'Active') {
                WelcomeBanner::where('welcomestatus', 'Active')->update(['welcomestatus' => 'Inactive']);
            }

            WelcomeBanner::create([
                'welcometext' => $request->welcometext,
                'welcomesubtext' => $request->welcomesubtext,
                'welcomestatus' => $status,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Welcome banner saved successfully!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Save failed: ' . $e->getMessage(),
            ]);
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'welcometext' => 'required|string|max:255',
            'welcomesubtext' => 'required|string',
            'welcomestatus' => 'required|in:active,inactive,Active,Inactive',
        ]);

        try {
            $banner = WelcomeBanner::findOrFail($request->input('id'));
            $status = ucfirst(strtolower($request->welcomestatus));

            if ($status === 'Active') {
                WelcomeBanner::where('id', '!=', $banner->id)
                    ->where('welcomestatus', 'Active')
                    ->update(['welcomestatus' => 'Inactive']);
            }

            $banner->update([
                'welcometext' => $request->welcometext,
                'welcomesubtext' => $request->welcomesubtext,
                'welcomestatus' => $status,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Welcome banner updated successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Update failed: ' . $e->getMessage(),
            ]);
        }
    }

    public function destroy($id)
    {
        $banner = WelcomeBanner::find($id);
        if ($banner) {
            $banner->delete();
            return response()->json(['success' => true, 'message' => 'Banner deleted successfully']);
        }
        return response()->json(['error' => true, 'message' => 'Banner not found'], 404);
    }

    public function show() 
    {
        $data = WelcomeBanner::all();

        return response()->json(['data' => $data]);
    }
}
