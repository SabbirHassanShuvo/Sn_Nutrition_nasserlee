<?php

namespace App\Http\Controllers\Api;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Str;
use App\Mail\PasswordResetMail;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Rules\PasswordRule;

class AuthController extends BaseController
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /** Register a User.
     * @return \Illuminate\Http\JsonResponse */
    public function register(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'role' => 'required|in:health_professional,user',
        ]);
     
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
     
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        
        $token = auth('api')->login($user);
        $success = $this->respondWithToken($token);
        $success['user'] =  $user;
   
        return $this->sendResponse($success, 'User registered and logged in successfully.');
    }
  
  
    /** Get a JWT via given credentials.
     * @return \Illuminate\Http\JsonResponse */
    public function login()
    {
        $credentials = request(['email', 'password']);
  
        if (! $token = auth('api')->attempt($credentials)) {
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        }
  
        $success = $this->respondWithToken($token);
   
        return $this->sendResponse($success, 'User login successfully.');
    }
  
    /** Get the authenticated User.
     * @return \Illuminate\Http\JsonResponse */
    public function profile()
    {
        $success = auth('api')->user();
   
        return $this->sendResponse($success, 'Refresh token return successfully.');
    }
  
    /** Log the user out (Invalidate the token).
     * @return \Illuminate\Http\JsonResponse */
    public function logout()
    {
        auth('api')->logout();
        
        return $this->sendResponse([], 'Successfully logged out.');
    }
  
    /** Refresh a token.
     * @return \Illuminate\Http\JsonResponse */
    public function refresh()
    {
        $success = $this->respondWithToken(auth('api')->refresh());
   
        return $this->sendResponse($success, 'Refresh token return successfully.');
    }
  
    /** Get the token array structure.
     * @param  string $token
     * @return \Illuminate\Http\JsonResponse */
    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ];
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return jsonErrorResponse('Validation failed', 422, $validator->errors()->toArray());
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return jsonErrorResponse('No user found with this email address.', 404);
        }

        // Generate a 6-digit OTP
        $otp = $this->otpService->generateOtp($request->email);

        $user->password_reset_otp = $otp;
        $user->password_reset_otp_is_verified = false;
        $user->password_reset_otp_expiry = now()->addMinutes($this->otpService->getTtl_min_time()); 
        $user->save();

        // Send OTP via email
        $this->otpService->sendOtpEmail($request->email, $otp);

        return jsonResponse(true, 'Password reset OTP has been sent to your email.', 200, ['OTP' => $otp]);
    }

    public function verifyOtp(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp' => 'required|string',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return jsonErrorResponse('Profile Update Validation failed', 422, $validator->errors()->toArray());
        }

        // Find the user by email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return jsonErrorResponse('No user found with this email address.', 404);
        }

        // Check if the OTP matches
        if ($user->password_reset_otp !== removeSpaces($request->otp)) {
            return jsonErrorResponse('Invalid OTP.', 400);
        }

        if (!$user->password_reset_otp) {
            return jsonErrorResponse('Unauthorizied OTP.', 401);
        }

        // Check if the OTP has expired
        if ($user->password_reset_otp_expiry < now()) {
            return jsonErrorResponse('OTP has expired.', 400);
        }
        
        $user->password_reset_otp_is_verified = true;
        $user->password_reset_otp_expiry = now()->addMinutes(5);
        $user->save(); 
        // OTP is valid, proceed to allow password reset
        return jsonResponse(true, 'OTP verified successfully. You can now reset your password with in the next 5 mins.', 200);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => ['required','string', 'confirmed', new PasswordRule],
        ]);

        if ($validator->fails()) {
            return jsonErrorResponse('Validation failed', 422, $validator->errors()->toArray());
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return jsonErrorResponse('No user found with this email address.', 404);
        }

        if (!$user->password_reset_otp_is_verified) {
            return jsonErrorResponse('Unauthorized attempt. Please verify OTP first.', 401);
        }

        if ($user->password_reset_otp_expiry < now()) {
            $user->password_reset_otp_is_verified = false;
            $user->save();
            return jsonErrorResponse('OTP verification session has expired. Please request a new OTP.', 400);
        }

        $user->password = Hash::make($request->password);
        $user->password_reset_otp = null;
        $user->password_reset_otp_expiry = null;
        $user->password_reset_otp_is_verified = false;
        $user->save();

        return jsonResponse(true, 'Password has been successfully reset.', 200);
    }
    
    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return jsonErrorResponse('Profile Update Validation failed', 422, $validator->errors()->toArray());
        }

        // Find user by email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return jsonErrorResponse('No user found with this email address.', 404);
        }

        // Generate a new 6-digit reset token
        $otp = $this->otpService->generateOtp($request->email);

        // Store the new token and set expiry time
        $user->password_reset_otp = $otp;
        $user->password_reset_otp_is_verified = false;
        $user->password_reset_otp_expiry = now()->addMinutes($this->otpService->getTtl_min_time());  // Token expires after 5 minutes
        $user->save();

        // Send the new token to the user's email
        // Mail::to($user->email)->queue(new PasswordResetMail($token));
        $this->otpService->sendOtpEmail($request->email, $otp);

        return jsonResponse(true, 'A new password reset OTP has been sent to your email.', 200, ['OTP' => $otp]);
    }

    
    public function profileRetrieval(Request $request)
    {
        try {
            $user = auth()->user();

            return jsonResponse(
                true,
                'User profile retrieved successfully.',
                200,
                $user->only(['id', 'name', 'email', 'avatar', 'address', 'phone', 'role', 'is_premium'])
            );
        } catch (Exception $e) {
            return jsonErrorResponse('Failed to retrieve user profile.', 500, ['error' => $e->getMessage()]);
        }
    }

     public function ProfileUpdate(Request $request)
    {
        $user = User::with('profile')->find(auth('api')->user()->id);
        $profile = $user->profile;

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|nullable|string|max:255',
            'avatar' => 'sometimes|nullable|image|mimes:jpg,jpeg,png,gif,svg,webp,ico,bmp,tiff',
            'address' => 'sometimes|nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return jsonErrorResponse(
                'Profile Update Validation failed',
                422,
                $validator->errors()->toArray()
            );
        }

        // Update User name
        if ($request->filled('name')) {
            $user->name = $request->name;
            $user->save();
        }

        // Update Profile fields
        if ($request->filled('address')) {
            $profile->address = $request->address;
        }

        // Avatar handle
        if ($request->hasFile('avatar')) {
            if ($profile->avatar) {
                fileDelete($profile->avatar);
            }

            $avatar = $request->file('avatar');
            $avatarPath = fileUpload($avatar, 'profile/avatar');

            $profile->avatar = $avatarPath;
        }

        $profile->save();

        return jsonResponse(
            true,
            'Profile updated successfully',
            200,
            [
                'name'    => $user->name,
                'email'   => $user->email,
                'avatar'  => $profile->avatar,
                'address' => $profile->address
            ]
        );
    }

    public function ChangePassword(Request $request)
    {
        // Create custom validator using Validator facade
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string',
            'password' => 'required|string|confirmed|min:8',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return jsonErrorResponse('Profile Update Validation failed', 422, $validator->errors()->toArray());
        }

        // Authenticate the user using JWT
        // $user = JWTAuth::parseToken()->authenticate();
        $user = auth('api')->user();

        if (!$user) {
            return jsonErrorResponse('User not found or unauthorized', 401);
        }

        // Check if the old password matches the current password
        if (!Hash::check($request->old_password, $user->password)) {
            return jsonErrorResponse('Old password is incorrect', 400);
        }

        // Hash the new password and save it to the database
        $user->password = Hash::make($request->password);
        $user->save();

        return jsonResponse(true, 'Password changed successfully', 200, [
            'name'   => $user->name,
            'email'  => $user->email,
            'avatar' => optional($user->profile)->avatar,
        ]);
    }
}
