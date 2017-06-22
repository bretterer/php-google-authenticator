<?php

namespace App\Http\Controllers;

use App\Utilities\Base32;
use App\Utilities\Otp;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index');
    }

    public function enroll()
    {
        $authenticatedUser = auth()->user();
        //Check to see if we are currently enrolled.
        if($authenticatedUser->enrolled) {
            return redirect()->back();
        }

        if($authenticatedUser->mfaKey == null) {
            $authenticatedUser->mfaKey = Base32::encode(str_random(32));
            $authenticatedUser->save();
        }

        return view('profile.enroll', ['qrUrl' => $this->qrUrl($authenticatedUser->mfaKey)]);

    }

    public function enrollVerify()
    {
        $authenticatedUser = auth()->user();
        if($authenticatedUser->enrolled || $authenticatedUser->mfaKey == null) {
            return redirect()->back();
        }

        $otpVerify = request()->get('otpVerify');
        $key = $authenticatedUser->mfaKey;

        $valid = Otp::verify($key, $otpVerify);
        if($valid) {
            $authenticatedUser->enrolled = true;
            $authenticatedUser->save();
        }

        return redirect()->route('profile');

    }

    public function unenroll()
    {
        $authenticatedUser = auth()->user();
        $authenticatedUser->enrolled = true;
        $authenticatedUser->mfaKey = true;
        $authenticatedUser->save();

        return redirect()->back();
    }

    private function qrUrl($key)
    {
        $authenticatedUser = auth()->user();
        $url = "http://chart.apis.google.com/chart";
        $url .= "?chs=200x200&chld=M|0&cht=qr&chl=otpauth://totp/";
        $url .= $authenticatedUser->name . " @ " . config('app.name'). "%3Fsecret%3D" . $key . '%26issuer%3D' . rawurlencode(config('app.url'));

        return $url;
    }
}
