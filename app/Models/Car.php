<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
