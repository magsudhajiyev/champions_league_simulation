<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Champion extends Model
{
    use HasFactory;

    protected $fillable = ['total_round'];

    public function fixtures(){
        return $this->hasMany(Fixture::class);
    }
}
