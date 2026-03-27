<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Item;
use App\Models\Message;

class Purchase extends Model
{
    use HasFactory;

    const STATUS_PENDING   = 'pending';   // 入力途中
    const STATUS_PAID      = 'paid';      // 決済完了
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'user_id',
        'item_id',
        'payment',
        'post_code',
        'address',
        'building',
        'status',
        'session_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
