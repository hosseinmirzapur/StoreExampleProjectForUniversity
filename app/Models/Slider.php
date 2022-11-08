<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Slider extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * @param $value
     * @return string
     */
    public function getImageAttribute($value): string
    {
        return Storage::url($value);
    }
}
