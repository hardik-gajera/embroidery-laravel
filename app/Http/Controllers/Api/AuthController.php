<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate([
            'mobile_no' => 'required|string|min:10|max:15',
        ]);

        $mobile = $request->mobile_no;
        $otp = rand(100000, 999999);
        
        // Store OTP in cache for 10 minutes
        Cache::put("otp_{$mobile}", $otp, 600);
        
        // Send SMS
        $this->sendOtpSms($mobile, $otp);
        
        // Check if user exists
        $userExists = Customer::where('mobile_no', $mobile)->exists();
        
        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully',
            'data' => [
                'user_exists' => $userExists,
                'otp_sent' => true
            ]
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'mobile_no' => 'required|string',
            'otp' => 'required|string|size:6',
        ]);

        $mobile = $request->mobile_no;
        $otp = $request->otp;
        
        $cachedOtp = Cache::get("otp_{$mobile}");
        
        if (!$cachedOtp || $cachedOtp != $otp) {
            throw ValidationException::withMessages([
                'otp' => ['Invalid or expired OTP.'],
            ]);
        }
        
        // Clear OTP from cache
        Cache::forget("otp_{$mobile}");
        
        // Check if user exists
        $customer = Customer::where('mobile_no', $mobile)->first();
        
        if ($customer) {
            // Existing user - login
            $token = $customer->createToken('mobile-app')->plainTextToken;
            
            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => [
                    'customer' => $customer,
                    'token' => $token,
                    'is_new_user' => false
                ]
            ]);
        } else {
            // New user - needs registration
            return response()->json([
                'success' => true,
                'message' => 'OTP verified. Please complete registration.',
                'data' => [
                    'otp_verified' => true,
                    'is_new_user' => true,
                    'mobile_no' => $mobile
                ]
            ]);
        }
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'mobile_no' => 'required|string|max:15|unique:customers,mobile_no',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile_no' => $request->mobile_no,
            'password' => $request->password,
        ]);
        
        $token = $customer->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registration completed successfully',
            'data' => [
                'customer' => $customer,
                'token' => $token,
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    public function profile(Request $request)
    {
        $customer = $request->user();
        
        return response()->json([
            'success' => true,
            'data' => [
                'customer' => $customer->load('package'),
                'has_active_package' => $customer->hasActivePackage(),
                'remaining_downloads' => $customer->hasActivePackage() ? 
                    ($customer->total_design - $customer->downloaded_design) : 0,
            ]
        ]);
    }
    
    private function sendOtpSms($mobile, $otp)
    {
        try {
            $config = config('sms');
            
            $headers = [
                "Content-Type" => "application/json",
                "key" => $config['sms_api']['key']
            ];
            
            $smsContent = str_replace('{otp}', $otp, $config['otp']['template']);
            
            $payload = [
                "listsms" => [[
                    "sms" => $smsContent,
                    "mobiles" => $mobile,
                    "senderid" => $config['sms_api']['sender_id'],
                    "entityid" => $config['sms_api']['entity_id'],
                    "tempid" => $config['sms_api']['template_id']
                ]]
            ];
            
            Http::withHeaders($headers)->post($config['sms_api']['url'], $payload);
        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Log::error('SMS sending failed: ' . $e->getMessage());
        }
    }
}