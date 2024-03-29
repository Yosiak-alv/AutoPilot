<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RepairStatus extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public $timestamps = false;

    public function repairs(): HasMany
    {
        return $this->hasMany(Repair::class);
    }
}
