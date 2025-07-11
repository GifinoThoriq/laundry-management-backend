<?php

namespace App\Models;

use App\Enums\ClothesColor;
use App\Enums\ClothesFabric;
use App\Enums\ClothesType;
use Illuminate\Database\Eloquent\Model;

class Clothes extends Model
{
    protected $fillable = [
        'description',
        'type',
        'fabric',
        'color',
        'quantity'
    ];

    //user relation
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'type' => ClothesType::class,
        'fabric' => ClothesFabric::class,
        'color' => ClothesColor::class
    ];
}
