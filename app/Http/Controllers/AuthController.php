<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller {
    public function loginForm() { return view('auth.login'); }
    public function registerForm() { return view('auth.register'); }
    public function login(Request $request) {
        $credentials = $request->validate(['email'=>['required','email'],'password'=>['required','string']]);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }
        return back()->withErrors(['email'=>'Invalid email or password.'])->onlyInput('email');
    }
    public function register(Request $request) {
        $data = $request->validate([
            'name'=>['required','string','max:100'],
            'email'=>['required','email','max:255','unique:users,email'],
            'password'=>['required','string','min:12','confirmed'],
        ]);
        $user=User::create(['name'=>$data['name'],'email'=>$data['email'],'password'=>Hash::make($data['password'])]);
        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->route('dashboard');
    }
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
