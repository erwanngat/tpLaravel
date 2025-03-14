<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dishe extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'image', 'user_id'];
    public $casts = ['description' => 'encrypted'];
    public function user(){
        return $this->belongsTo(User::class);
    }
}
