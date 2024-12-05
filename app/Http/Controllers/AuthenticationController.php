<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthenticationController extends Controller
{
    public function getLogin(Request $request)
    {
        return view('authentication.login');
    }
    public function postLogin(Request $request)
    {
        $request->validate([
            'email' => ['required', 'regex:/^[a-zA-Z0-9]+@(gmail\.com)$/'],
            'password' => ['required', 'max:50'],
        ]);

        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];

        try {
            if (Auth::attempt($credentials)) {
                $user = Auth::user();

                // Kiểm tra is_admin và chuyển hướng
                if ($user->is_admin) {
                    return redirect()->route('admin.overview');
                } else {
                    return redirect()->route('product.homeShop');
                }
            } else {
                return redirect()->back()->withInput()->withErrors('Đăng nhập không thành công');
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function getRegister(Request $request)
    {
        return view('authentication.register');
    }
    public function postRegister(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:50'],
            'email' => ['required', 'unique:users', 'regex:/^[a-zA-Z0-9]+@(gmail\.com)$/'],
            'pass' => ['required', 'max:50'],
            're-pass' => ['required', 'same:pass'],
            'phone' => ['required', 'regex:/^(\+\d{1,3}[- ]?)?\d{10,13}$/'],
            'address' => ['required', 'max:255']
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->pass),
            'is_admin' => 0
        ];
        $user = User::create($data);
        return redirect()->route('authentication.login')->withMessage('login sucess');
    }

    public function logOut()
    {
        Auth::logout();
        return redirect()->route('product.homeShop');
    }
}
