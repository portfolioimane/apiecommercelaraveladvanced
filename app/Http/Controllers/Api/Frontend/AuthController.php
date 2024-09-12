<?php
namespace App\Http\Controllers\Api\Frontend;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log; // Import Log for debugging
use Illuminate\Support\Facades\Auth; // Import Auth facade

class AuthController extends Controller
{
  

  public function getUser(Request $request)
{
    // Check if the user is authenticated
    if ($request->user()) {
        return response()->json([
            'message' => 'User is authenticated',
            'user' => $request->user()
        ], 200);
    } else {
        return response()->json([
            'message' => 'User is not authenticated'
        ], 401);
    }
}

    /**
     * Handle user login
     */
    public function login(Request $request)
    {
        // Log incoming request data for debugging
        Log::info('Login request received', ['request_data' => $request->except('password')]);

        // Validate request
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            Log::error('Validation failed', ['errors' => $validator->errors()]); // Log validation errors
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Attempt to authenticate the user
        if (Auth::attempt($request->only('email', 'password'))) {
            // Authentication was successful
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            Log::info('Login successful', ['user_id' => $user->id, 'token' => $token]);

            // Return success response with token
            return response()->json([
                'message' => 'Login successful!',
                'user' => $user,
                'token' => $token,
            ], 200);
        } else {
            // Authentication failed
            Log::warning('Login failed', ['email' => $request->email]);
            return response()->json(['error' => 'Invalid email or password.'], 401);
        }
    }


  /**
     * Handle user registration
     */

    public function register(Request $request)
    {
        // Log incoming request data for debugging (avoid logging sensitive info in production)
        Log::info('Register request received', ['request_data' => $request->except('password')]);

        // Validate request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // `confirmed` ensures password confirmation matches
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            Log::error('Validation failed', ['errors' => $validator->errors()]); // Log validation errors
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            // Create the user
            Log::info('Creating user');
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password), // Hash the password
            ]);

            Log::info('User created successfully', ['user_id' => $user->id]);

            // Create token for the user
            $token = $user->createToken('auth_token')->plainTextToken;

            Log::info('Token generated successfully', ['token' => $token]);

            // Return success response
            return response()->json([
                'message' => 'Registration successful!',
                'user' => $user,
                'token' => $token,
            ], 201);

        } catch (\Exception $e) {
            // Catch and log any errors during the user creation process
            Log::error('Error during registration', ['exception' => $e->getMessage()]);
            return response()->json(['error' => 'Registration failed. Please try again.'], 500);
        }
    }
}
