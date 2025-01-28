<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class TailwindCode extends Model
{
    //
    protected $fillable = ['title', 'slug', 'description', 'tailwind', 'user_id'];

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}



