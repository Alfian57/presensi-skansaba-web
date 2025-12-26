<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\StudentResource;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\StudentLoginRequest;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/auth/login",
     *     tags={"Authentication"},
     *     summary="Login to get access token",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"login","password","device_name","device_id"},
     *
     *             @OA\Property(property="login", type="string", example="siswa1"),
     *             @OA\Property(property="password", type="string", example="password"),
     *             @OA\Property(property="device_name", type="string", example="Samsung Galaxy S21"),
     *             @OA\Property(property="device_id", type="string", example="abc123def456")
     *         )
     *     ),
     *
     *     @OA\Response(response=200, description="Login successful"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function login(StudentLoginRequest $request)
    {

        $user = User::where('username', $request->login)
            ->orWhere('email', $request->login)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'login' => ['Kredensial yang diberikan salah.'],
            ]);
        }

        $student = Student::where('user_id', $user->id)->first();
        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya siswa yang dapat login melalui aplikasi mobile.',
            ], 403);
        }

        if (!$student->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda tidak aktif. Hubungi admin.',
            ], 403);
        }

        if ($student->active_device_id && $student->active_device_id !== $request->device_id) {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda sudah terdaftar di perangkat lain.',
            ], 403);
        }

        if (!$student->active_device_id) {
            $student->update([
                'active_device_id' => $request->device_id,
                'device_registered_at' => now(),
            ]);
        }

        $user->tokens()->delete();
        $token = $user->createToken($request->device_name)->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'data' => [
                'token' => $token,
                'student' => new StudentResource($student->load(['user', 'classroom'])),
            ],
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/logout",
     *     tags={"Authentication"},
     *     summary="Logout and revoke token",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(response=200, description="Logout successful"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil.',
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/auth/me",
     *     tags={"Authentication"},
     *     summary="Get authenticated user profile",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=404, description="Student data not found")
     * )
     */
    public function me(Request $request)
    {
        $user = $request->user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student data not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new StudentResource($student->load(['user', 'classroom'])),
        ]);
    }
}
