<?php

namespace App\Http\Controllers\Api;

use App\Models\Place;
use App\Models\ParkingSpot;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ParkingController extends Controller
{
        // عرض الركنات لمكان معين
    public function index(Place $place)
    {
        return response()->json([
            'success' => true,
            'data' => $place->parkingSpots
        ]);
    }

    // إضافة ركنة لمكان
    public function store(Request $request, Place $place)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $validated['place_id'] = $place->id;

        $spot = ParkingSpot::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة الركنة بنجاح',
            'data' => $spot
        ], 201);
    }

    // حجز الركنة
    public function reserve(Place $place, ParkingSpot $spot)
    {
        if ($spot->place_id != $place->id) {
            return response()->json(['error' => 'الركنة لا تخص هذا المكان'], 403);
        }

        if (!$spot->is_available) {
            return response()->json([
                'success' => false,
                'message' => 'الركنة محجوزة بالفعل'
            ], 409);
        }

        $spot->update(['is_available' => false]);

        return response()->json([
            'success' => true,
            'message' => 'تم حجز الركنة بنجاح',
            'data' => $spot
        ]);
    }

    // إلغاء الحجز
    public function release(Place $place, ParkingSpot $spot)
    {
        if ($spot->place_id != $place->id) {
            return response()->json(['error' => 'الركنة لا تخص هذا المكان'], 403);
        }

        $spot->update(['is_available' => true]);

        return response()->json([
            'success' => true,
            'message' => 'تم إلغاء الحجز',
            'data' => $spot
        ]);
    }

    // حذف ركنة
    public function destroy(Place $place, ParkingSpot $spot)
    {
        if ($spot->place_id != $place->id) {
            return response()->json(['error' => 'الركنة لا تخص هذا المكان'], 403);
        }

        $spot->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الركنة'
        ]);
    }
}
