<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Image extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = ['id'];
    public function cars () : BelongsToMany
    {
        return $this->belongsToMany(Car::class);
    }
}
