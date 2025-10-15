<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['title','is_done','user_id'];
    protected $casts = ['is_done'=>'boolean'];


    public function title():Attribute{
    return Attribute::make(
        set: fn ($v) => preg_replace(
            '/[  ]+/u',                  // 半角/全角スペースの連続
            ' ',                          // 半角スペース1個に圧縮
            mb_convert_kana(trim((string)$v), 's') // 前後trim + 全角→半角
        )
    );
    }

    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }
}
