<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Purchase;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Category;

class Item extends Model
{
    use HasFactory;
    protected $fillable = [
            'user_id',
            'item_url',
            'name',
            'brand_name',
            'description',
            'price',
            'condition',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function purchases()
    {
        return $this->hasOne(Purchase::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
