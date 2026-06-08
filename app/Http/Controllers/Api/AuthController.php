<?php

namespace App\Http\Controllers\Api;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AuthController extends BaseApiController
{
    public function sendOtp(Request $request)
    {
        try {
            $validator = validator($request->all(), [
                'mobile_no' => 'required|string|min:10|max:15',
            ]);

            if ($validator->fails()) {
                return $this->validationErrorResponse($validator->errors()->toArray());
            }

            $mobile = $request->mobile_no;
            $otp = rand(100000, 999999);
            
            // Store OTP in cache for 10 minutes
            Cache::put("otp_{$mobile}", $otp, 600);
            
            // Send SMS
            $this->sendOtpSms($mobile, $otp);
            
            // Check if user exists
            $userExists = Customer::where('mobile_no', $mobile)->exists();
            
            return $this->successResponse('OTP sent successfully', [
                'user_exists' => $userExists,
                'otp_sent' => true
            ]);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Send OTP');
        }
    }

    public function verifyOtp(Request $request)
    {
        try {
            $validator = validator($request->all(), [
                'mobile_no' => 'required|string',
                'otp' => 'required|string|size:6',
            ]);

            if ($validator->fails()) {
                return $this->validationErrorResponse($validator->errors()->toArray());
            }

            $mobile = $request->mobile_no;
            $otp = $request->otp;
            
            $cachedOtp = Cache::get("otp_{$mobile}");
            
            if (!$cachedOtp || $cachedOtp != $otp) {
                return $this->errorResponse('Invalid or expired OTP', [], 400);
            }
            
            // Clear OTP from cache
            Cache::forget("otp_{$mobile}");
            
            // Check if user exists
            $customer = Customer::where('mobile_no', $mobile)->first();
            
            if ($customer) {
                // Existing user - login
                $token = $customer->createToken('mobile-app')->plainTextToken;
                
                return $this->successResponse('Login successful', [
                    'customer' => $customer,
                    'token' => $token,
                    'is_new_user' => false
                ]);
            } else {
                // New user - needs registration
                return $this->successResponse('OTP verified. Please complete registration.', [
                    'otp_verified' => true,
                    'is_new_user' => true,
                    'mobile_no' => $mobile
                ]);
            }
        } catch (\Exception $e) {
            return $this->handleException($e, 'Verify OTP');
        }
    }

    public function register(Request $request)
    {
        try {
            $validator = validator($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:customers,email',
                'mobile_no' => 'required|string|max:15|unique:customers,mobile_no',
                'password' => 'required|string|min:6|confirmed',
            ]);

            if ($validator->fails()) {
                return $this->validationErrorResponse($validator->errors()->toArray());
            }

            $customer = Customer::create([
                'name' => $request->name,
                'email' => $request->email,
                'mobile_no' => $request->mobile_no,
                'password' => $request->password,
            ]);
            
            $token = $customer->createToken('mobile-app')->plainTextToken;

            return $this->successResponse('Registration completed successfully', [
                'customer' => $customer,
                'token' => $token,
            ]);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Registration');
        }
    }

    public function checkSession(Request $request)
    {
        try {
            $customer = $request->user();
            return $this->successResponse('Session active', [
                'customer' => $customer,
                'is_active' => true
            ]);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Check Session');
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return $this->successResponse('Logged out successfully');
        } catch (\Exception $e) {
            return $this->handleException($e, 'Logout');
        }
    }

    public function profile(Request $request)
    {
        try {
            $customer = $request->user();
            
            return $this->successResponse('Profile retrieved successfully', [
                'customer' => $customer->load('package'),
                'has_active_package' => $customer->hasActivePackage(),
                'remaining_downloads' => $customer->hasActivePackage() ? 
                    ($customer->total_design - $customer->downloaded_design) : 0,
            ]);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Get Profile');
        }
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
            Log::error('SMS sending failed: ' . $e->getMessage());
        }
    }
}