<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;
class Brand extends EloquentModel
{
    use HasFactory;
    protected $guarded = ['id'];
    public function models():HasMany
    {
        return $this->hasMany(Model::class);
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
                ->orWhere('id','like','%'.$search.'%')
                ->orWhere('created_at','like','%'.$search.'%')     
                ->orWhere('updated_at','like','%'.$search.'%')
            );
        });
    }
}
