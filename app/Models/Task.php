<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = ['title','is_done'];
    protected $casts = ['is_done'=>'boolean'];

    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }
}
