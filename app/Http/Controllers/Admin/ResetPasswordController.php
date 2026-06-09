<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ResetPasswordController extends Controller
{
    public function showResetForm($token)
    {
        $email = request('email');
        
        $resetRecord = DB::table('password_reset_tokens')
            ->where('token', $token)
            ->where('email', $email)
            ->first();

        if (!$resetRecord) {
            return redirect()->route('admin.login')->with('error', 'Invalid or expired reset link.');
        }

        if (Carbon::parse($resetRecord->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return redirect()->route('admin.login')->with('error', 'Reset link has expired.');
        }

        return view('admin.auth.reset-password', compact('token', 'email'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:admins,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $resetRecord = DB::table('password_reset_tokens')
            ->where('token', $request->token)
            ->where('email', $request->email)
            ->first();

        if (!$resetRecord) {
            return back()->with('error', 'Invalid reset token.');
        }

        $admin = Admin::where('email', $request->email)->first();
        $admin->password = Hash::make($request->password);
        $admin->save();

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('admin.login')->with('success', 'Password has been reset successfully.');
    }
}