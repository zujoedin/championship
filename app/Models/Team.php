<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];
    public $timestamps = false;

    public function pair()
    {
        return $this->belongsTo(Pair::class,'id','team_id');
    }

    
}
