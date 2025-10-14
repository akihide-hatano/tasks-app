<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;
    
    protected $fillable = ['title','is_done'];
    protected $casts = ['is_done'=>'boolean'];


    public function title():Attribute{
        return Attribute::make(
             set: fn ($v) => mb_convert_kana(trim((string)$v), 's') // 前後空白除去＋スペース正規化
        );
    }
    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }
}
