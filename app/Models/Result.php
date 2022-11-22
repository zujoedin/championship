<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;
    protected $fillable = [
        'team_id_1',
        'team_id_2',
        'score_1',
        'score_2',
    ];
    public $timestamps = false;

    public function team_1()
    {
        return $this->belongsTo(Team::class,'team_id_1');
    }

    public function team_2()
    {
        return $this->belongsTo(Team::class,'team_id_2');
    }
}
