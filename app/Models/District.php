<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public $timestamps = false;

    public function town():BelongsTo
    {
        return $this->belongsTo(Town::class);
    }
    public function branches():HasMany
    {
        return $this->hasMany(Branch::class);
    }
    public function workShops():HasMany
    {
        return $this->hasMany(WorkShop::class);
    }
}
