<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Models\User;


class UserController extends Controller
{
    public function index()
    {
        return view('admin.user.listuser');
    }

    public function store(Request $request) 
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'lname' => 'required',
                'fname' => 'required',
                'mname' => 'required',
                'username' => 'required|string|min:5|unique:users,username',
                'password' => 'required|string|min:5',
                'gender' => 'required',
                'role' => 'required',
            ]);

            $userName = $request->input('username'); 
            $existingUser = User::where('username', $userName)->first();

            if ($existingUser) {
                return response()->json(['error' => true, 'message' => 'User already exists!']);
            }

            try {

                $fname = $request->input('fname');
                $lname = $request->input('lname');
                //$avatarUrl = 'https://avatar.iran.liara.run/username?username=' . rawurlencode($fname) . '+' . rawurlencode($lname);

                $user = User::create([
                    'lname' => $request->input('lname'),
                    'fname' => $request->input('fname'),
                    'mname' => $request->input('mname'),
                    'username' => $userName,
                    'password' => Hash::make($request->input('password')),
                    'role' => $request->input('role'),
                    'gender' => $request->input('gender'),
                    'posted_by' => Auth::user()->id,
                    'avatar' => 'Yes',
                    'remember_token' => Str::random(60),
                ]);

                return response()->json(['success' => true, 'message' => 'User stored successfully!']);
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to store User!']);
            }
        }
    }

    public function show() 
    {
        $data = User::where('ustatus', '!=', '3')->get()->map(function ($user) {
            $initials = strtoupper(substr($user->fname, 0, 1) . substr($user->lname, 0, 1));
            $colors = ['#4F46E5', '#16A34A', '#DC2626', '#F59E0B', '#2563EB', '#9333EA'];
            $color = $colors[$user->id % count($colors)];
            $user->avatar = '
            <div style="
                width:40px;
                height:40px;
                border-radius:50%;
                background-color:' . $color . ';
                color:white;
                display:flex;
                align-items:center;
                justify-content:center;
                font-weight:bold;
                font-size:14px;
                text-transform:uppercase;
            ">
                ' . $initials . '
            </div>
        ';
            return $user;
        });

        return response()->json(['data' => $data]);
    }

    public function update(Request $request) {
        $user = User::find($request->id);
        
        $request->validate([
            'id' => 'required',
            'lname' => 'required',
            'fname' => 'required',
            'mname' => 'required',
            'role' => 'required',
            'gender' => 'required',
        ]);

        try {
            $userName = $request->input('username');
            $existingUser = User::where('username', $userName)->where('id', '!=', $request->input('id'))->first();

            if ($existingUser) {
                return response()->json(['error' => true, 'message' => 'Username already exists!']);
            }

            $user = User::findOrFail($request->input('id'));
            $user->update([
                'lname' => $request->input('lname'),
                'fname' => $request->input('fname'),
                'mname' => $request->input('mname'),
                'username' => $userName,
                'role' => $request->input('role'),
                'gender' => $request->input('gender'),
            ]);

            return response()->json(['success' => true, 'message' => 'User updated successfully!']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to update User!']);
        }
    }

    public function destroy($id) {
        $usr = User::find($id);
        if ($usr) {
            $usr->ustatus = 3;
            $usr->save();
            return response()->json(['success'=> true, 'message'=>'User deleted successfully']);
        }
        return response()->json(['error'=> true, 'message'=>'User not found']);
    }

    public function userUpdatePassword(Request $request) {
        $user = User::find($request->id);
        
        $request->validate([
            'id' => 'required',
            'password' => 'required|string|min:5',
        ]);

        try {
            $user = User::findOrFail($request->input('id'));
            $user->update([
                'password' => Hash::make($request->input('password'))
            ]);

            return response()->json(['success' => true, 'message' => 'User Password updated successfully!']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to update User Password!']);
        }
    }

    public function userUpdateStatus(Request $request) {
        $user = User::find($request->id);
        
        $request->validate([
            'id' => 'required',
            'ustatus' => 'required',
        ]);

        try {
            $user = User::findOrFail($request->input('id'));
            $user->update([
                'ustatus' => $request->input('ustatus'),
            ]);

            return response()->json(['success' => true, 'message' => 'User Password updated successfully!']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to update User Password!']);
        }
    }
}
