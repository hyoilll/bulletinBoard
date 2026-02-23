<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\RegistrationOtpMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     * OTPコードをメール送信し、OTP入力画面へリダイレクト。
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $cacheKey = 'pending_reg_' . Str::random(32);

        cache()->put($cacheKey, [
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'otp'      => $otp,
        ], now()->addMinutes(10));

        session(['pending_reg_key' => $cacheKey]);

        Mail::to($validated['email'])->send(new RegistrationOtpMail($otp));

        return redirect()->route('register.otp');
    }

    /**
     * Display the OTP input form.
     */
    public function showOtpForm(): View|RedirectResponse
    {
        $key = session('pending_reg_key');

        if (!$key || !cache()->has($key)) {
            return redirect()->route('register')
                ->withErrors(['email' => 'セッションが切れました。再度登録してください。']);
        }

        return view('auth.registration-otp');
    }

    /**
     * Verify OTP and create the user.
     */
    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate(['otp' => ['required', 'digits:6']]);

        $cacheKey = session('pending_reg_key');
        $data = $cacheKey ? cache()->get($cacheKey) : null;

        if (!$data) {
            return redirect()->route('register')
                ->withErrors(['email' => 'コードが期限切れです。再度登録してください。']);
        }

        if ($request->otp !== $data['otp']) {
            return back()->withErrors(['otp' => 'コードが正しくありません。']);
        }

        if (User::where('email', $data['email'])->exists()) {
            cache()->forget($cacheKey);
            session()->forget('pending_reg_key');
            return redirect()->route('register')
                ->withErrors(['email' => 'このメールアドレスは既に登録されています。']);
        }

        $user = User::create([
            'name'              => $data['name'],
            'email'             => $data['email'],
            'password'          => $data['password'],
            'email_verified_at' => now(),
        ]);

        cache()->forget($cacheKey);
        session()->forget('pending_reg_key');
        Auth::login($user);

        return redirect()->route('posts.index')->with('success', '登録が完了しました！');
    }
}