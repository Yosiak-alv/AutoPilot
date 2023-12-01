<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Model extends EloquentModel
{
    use HasFactory;
    protected $guarded = ['id'];
    public $timestamps = false;

    public function brand():BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
    public function cars():HasMany
    {
        return $this->hasMany(Car::class);
    }
}
