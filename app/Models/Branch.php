<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function users():HasMany
    {
        return $this->hasMany(User::class);
    }
    public function district():BelongsTo
    {
        return $this->belongsTo(District::class);
    }
    public function cars():HasMany
    {
        return $this->hasMany(Car::class);
    }
}
