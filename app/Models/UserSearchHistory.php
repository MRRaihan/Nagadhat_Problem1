<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSearchHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'search_keyword',
        'searched_at',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }

}
