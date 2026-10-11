<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Models\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        return view('admin.customer.listcustomer');
    }

    public function show()
    {
        $data = Customer::orderBy('created_at', 'DESC')->get();

        return response()->json(['data' => $data]);
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'cstatus' => 'required|in:1,2',
        ]);

        try {
            $customer = Customer::findOrFail($request->input('id'));
            $customer->update(['cstatus' => $request->input('cstatus')]);

            return response()->json(['success' => true, 'message' => 'Customer status updated successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to update customer status!'], 404);
        }
    }
}
