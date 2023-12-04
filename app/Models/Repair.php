<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;
class Repair extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function car():BelongsTo
    {
        return $this->belongsTo(Car::class);
    }
    public function status():BelongsTo
    {
        return $this->belongsTo(RepairStatus::class, 'repair_status_id');
    }
    public function work_shop():BelongsTo
    {
        return $this->belongsTo(WorkShop::class, 'work_shop_id');
    }
    public function details():HasMany
    {
        return $this->hasMany(RepairDetails::class,'repair_id');
    }
    public function getCreatedAtAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->diffForHumans();
    }
    public function getUpdatedAtAttribute()
    {
        return Carbon::parse($this->attributes['updated_at'])->diffForHumans();
    }
}
