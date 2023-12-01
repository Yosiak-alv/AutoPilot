<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RepairDetails extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    
    public function repair():BelongsTo
    {
        return $this->belongsTo(Repair::class);
    }
}
