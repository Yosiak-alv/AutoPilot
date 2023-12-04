<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Znck\Eloquent\Relations\BelongsToThrough;
use Znck\Eloquent\Traits\BelongsToThrough as BelongsToThroughTrait;
use Carbon\Carbon;
class Branch extends Model
{
    use HasFactory, BelongsToThroughTrait;
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
    public function town(): BelongsToThrough
    {
        return $this->belongsToThrough(Town::class, District::class);
    }

    public function state(): BelongsToThrough
    {
        return $this->belongsToThrough(State::class, [Town::class,District::class]);
    }

    public function getCreatedAtAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->diffForHumans();
    }
    public function getUpdatedAtAttribute()
    {
        return Carbon::parse($this->attributes['updated_at'])->diffForHumans();
    }

    public function scopeFilter($query , array $filters)
    {
        $query->when($filters['search'] ?? false, function( $query, $search){
            $query->where(fn($query) =>
                $query->where('name','like','%'.$search.'%')
                ->orWhere('telephone','like','%'.$search.'%')
                ->orWhere(function ($query) use ($search) {
                    $query->where(function ($query) use ($search) {
                        if ($search === 'si') { //revisar
                            $query->where('main', '1');
                        } elseif ($search === 'no') {
                            $query->where('main', '0');
                        }
                    });
                })
            )->orWhereHas('district', function ($query) use ($search) {
                $query->where('districts.name', 'like', '%' . $search . '%');
            })->orWhereHas('town', function ($query) use ($search) {
                $query->where('towns.name', 'like', '%' . $search . '%');
            })->orWhereHas('state', function ($query) use ($search) {
                $query->where('states.name', 'like', '%' . $search . '%');
            });
        });
    }
}
/* 
 */