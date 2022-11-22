<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mortal;
use App\Models\Team;
use App\Models\Pair;
use App\Models\Result;
use DB;
use Illuminate\Support\Facades\Auth;

class ConfigController extends Controller
{
    public function getMortals(Request $request)
    {   
        
        $data = Mortal::get();
        return $data;
    }

    public function getTeams(Request $request)
    {   
        
        $data = Team::get();
        return $data;
    }

    public function getPairs(Request $request)
    {   
        $data = Pair::with('mortal')->with('team')->get();
        return $data;
    }

    public function postPair(Request $request)
    {   
        
        $team_id = $request->team_id;
        $mortal_id = $request->mortal_id;

        $pair = new Pair;
        $pair->team_id = $team_id;
        $pair->mortal_id = $mortal_id;
        $pair->save();

    }

    public function postMortal(Request $request)
    {   
        
        $name = $request->mortal;
        $mortal = new Mortal;
        $mortal->name = $name;
        $mortal->points = 0;
        $mortal->additional_points = 0;
        $mortal->save();

    }

    public function postTeam(Request $request)
    {   
        
        $name = $request->team;

        $team = new Team;
        $team->name = $name;
        $team->save();

    }

    public function postResult(Request $request)
    {   
        
        $team_score_1 = $request->team_score_1;
        $team_id_1 = $request->team_id_1;
        $team_score_2 = $request->team_score_2;
        $team_id_2 = $request->team_id_2;

        $result = new Result;
        $result->score_1 = $team_score_1;
        $result->score_2 = $team_score_2;
        $result->team_id_1 = $team_id_1;
        $result->team_id_2 = $team_id_2;
        $result->save();

        if($team_score_1!=$team_score_2){
            if($team_score_1>$team_score_1){
                $team_id = $team_id_1;
            }else{
                $team_id = $team_id_2;
            }
    
            $pair = Pair::where('team_id', $team_id)->first('mortal_id');
            if($pair){
                $mortal_id = $pair->mortal_id;
                $mortal = Mortal::where('id', $mortal_id)->increment('points', 1);  
            }
               
        }
       

    }

    public function getResults(Request $request)
    {   
        $data = Result::with('team_1.pair.mortal')->with('team_2.pair.mortal')->get();
        return $data;
    }

    public function getTable(Request $request)
    {   
        
        $data = Mortal::orderBy(DB::raw("`points` + `additional_points`"), 'desc')->get();
        return $data;
    }

    public function getTableTeams(Request $request)
    {   
        
        $data = Mortal::with('teams.team')->get();
        return $data;
    }

    public function reduceAdditionalScore(Request $request)
    {   
        
        $id = $request->mortal_id;
        $mortal = Mortal::find($id);
        $mortal->additional_points = $mortal->additional_points - 1;
        $mortal->save();
      

    }

    public function addAdditionalScore(Request $request)
    {   
        
        $id = $request->mortal_id;
        $mortal = Mortal::find($id);
        $mortal->additional_points = $mortal->additional_points + 1;
        $mortal->save();


    }
}
