<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    public function showForgotForm()
    {
        return view('admin.auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:admins,email',
        ], [
            'email.exists' => 'No admin account found with this email.',
        ]);

        $token = Str::random(64);

       
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        
        $resetLink = route('admin.password.reset', ['token' => $token, 'email' => $request->email]);
        
        Mail::send('emails.admin-reset-password', ['resetLink' => $resetLink], function($message) use($request) {
            $message->to($request->email);
            $message->subject('Reset Your Admin Password - EventHub');
        });

       return back()
    ->with('success', 'Reset link has been sent to your email.');
    
    }
}