<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Znck\Eloquent\Relations\BelongsToThrough;
use Znck\Eloquent\Traits\BelongsToThrough as BelongsToThroughTrait;
class WorkShop extends Model
{
    use HasFactory, BelongsToThroughTrait;
    protected $guarded = ['id'];
    public function repairs():HasMany
    {
        return $this->hasMany(Repair::class);
    }
    public function district():BelongsTo
    {
        return $this->belongsTo(District::class);
    }
    public function town(): BelongsToThrough
    {
        return $this->belongsToThrough(Town::class, District::class);
    }

    public function state(): BelongsToThrough
    {
        return $this->belongsToThrough(State::class, [Town::class,District::class]);
    }
}
