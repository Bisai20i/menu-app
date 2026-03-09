<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('master.dashboard');
        }
        return view('auth.login');
    }

    /**
     * Handle admin login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email|exists:admins,email',
            'password' => 'required|string|min:6',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('master.dashboard'))
                ->with('success', 'Welcome back, ' . Auth::guard('admin')->user()->name);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email', 'remember'));
    }

    /**
     * Show the registration form.
     */
    public function showRegisterForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('master.dashboard');
        }
        return view('auth.register');
    }

    /**
     * Handle admin registration request.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:8|confirmed',
            'terms'    => 'accepted',
        ]);

        $admin = Admin::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'admin', // Default role
        ]);

        Auth::guard('admin')->login($admin);

        return redirect()->route('master.dashboard')
            ->with('success', 'Account created successfully. Welcome!');
    }

    /**
     * Handle admin logout.
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('master.login')
            ->with('success', 'Logged out successfully.');
    }

    /**
     * Placeholder for forget password functionality.
     */
    public function forgetPassword(Request $request)
    {
        return back()->with('info', 'Password reset feature is coming soon.');
    }
}
