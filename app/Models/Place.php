<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory ;
    protected $fillable = [
        'name',
        'street',
        'image',
        'description',
        'price',
        'lat',
        'lng',
        'full_address',
        'city',
        'country',
        'google_maps_url'
    ];

    public function getImageUrlAttribute()
{
    return $this->image ? asset('storage/' . $this->image) : null;
}


    protected $casts = [
        'lat' => 'float',
        'lng' => 'float',
        'price' => 'float'
    ];

    // دالة مساعدة للحصول على الإحداثيات كـ array
    public function getCoordinatesAttribute()
    {
        return [
            'lat' => $this->lat,
            'lng' => $this->lng
        ];
    }
    
    public function parkingSpots()
{
    return $this->hasMany(ParkingSpot::class);
}


    // دالة لإنشاء رابط خرائط جوجل
    public function getGoogleMapsLinkAttribute()
    {
        if ($this->google_maps_url) {
            return $this->google_maps_url;
        }

        return "https://www.google.com/maps?q={$this->lat},{$this->lng}";
    }
}
