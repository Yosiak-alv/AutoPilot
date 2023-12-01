<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Town extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public $timestamps = false;

    public function state():BelongsTo
    {
        return $this->belongsTo(State::class);
    }
    public function districts(): HasMany
    {
        return $this->hasMany(District::class);
    }
}
