<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;
class Car extends EloquentModel
{
    use HasFactory;
    protected $guarded = ['id'];
    public function model():BelongsTo
    {
        return $this->belongsTo(Model::class);
    }
    public function branch():BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
    public function repairs():HasMany
    {
        return $this->hasMany(Repair::class);
    }
    public function getImageUrl()
    {
        if($this->image){
            /* return $this->image; */
            return url('storage/'.$this->image);
        }
        return 'https://th.bing.com/th/id/R.8e05d07418065f8feb3744ddf9858b7b?rik=kKtID2KoJy0JUg&riu=http%3a%2f%2fpixelartmaker-data-78746291193.nyc3.digitaloceanspaces.com%2fimage%2fb0afceaabed0502.png&ehk=3UjLkVS%2biMBlF%2fouFc1iKxjZHLQ9SlPybC%2fFLb1U16M%3d&risl=&pid=ImgRaw&r=0';
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
                $query->where('plates','like','%'.$search.'%')
                    ->orWhere('year','like','%'.$search.'%')
            )->orWhereHas('model',fn($query) =>
                $query->where('name','like','%'.$search.'%')
                    ->orWhereHas('brand',fn($query) =>
                        $query->where('name','like','%'.$search.'%')
                    )
            )->orWhereHas('branch',fn($query) =>
                $query->where('name','like','%'.$search.'%')
            );
        });
    }
}
