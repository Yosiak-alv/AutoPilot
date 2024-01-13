<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class File extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function cars () : BelongsToMany
    {
        return $this->belongsToMany(Car::class);
    }
    public function repairs () : BelongsToMany
    {
        return $this->belongsToMany(Repair::class);
    }
}
