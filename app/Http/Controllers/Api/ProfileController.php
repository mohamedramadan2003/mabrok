<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\UpdateRequest;

class ProfileController extends Controller
{
    /**
     * Update the user's profile.
     */
    public function update(UpdateRequest $request)
    {
        try {
            // طريقة 1: التحقق من المستخدم أولاً
            $user = $request->user();
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'المستخدم غير مسجل الدخول'
                ], 401);
            }

            $validated = $request->validated();

            // تحديث البيانات
            $user->name = $validated['name'] ?? $user->name;
            $user->email = $validated['email'] ?? $user->email;
            $user->phone = $validated['phone'] ?? $user->phone;

            // حفظ التغييرات
            $user->save();

            // جلب البيانات المحدثة
            $user->refresh();

            return response()->json([
                'status' => 'success',
                'message' => 'تم تحديث الملف الشخصي بنجاح',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'updated_at' => $user->updated_at->format('Y-m-d H:i:s'),
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء التحديث',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
