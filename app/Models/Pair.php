<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pair extends Model
{
    use HasFactory;
    protected $fillable = [
        'mortal_id',
        'team_id',
    ];
    public $timestamps = false;

    public function mortal()
    {
        return $this->belongsTo(Mortal::class,'mortal_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class,'team_id');
    }

    
}
