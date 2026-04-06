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
            'welcomestatus'  => 'required|in:active,inactive',
        ]);

        try {
            WelcomeBanner::create([
                'welcometext' => $request->welcometext,
                'welcomesubtext' => $request->welcomesubtext,
                'welcomestatus' => $request->welcomestatus,
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

    public function show() 
    {
        $data = WelcomeBanner::all();

        return response()->json(['data' => $data]);
    }
}
