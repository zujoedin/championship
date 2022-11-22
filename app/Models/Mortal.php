<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mortal extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'points',
        'additional_points'
    ];
    public $timestamps = false;

    public function teams() {

        return $this->hasMany(Pair::class,'mortal_id');

    }
}
