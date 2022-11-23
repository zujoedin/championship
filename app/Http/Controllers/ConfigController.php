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
           
            if($team_score_1>$team_score_2){               
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

    public function deleteTeam(Request $request)
    {   
        
        $id = $request->id;



        // $team = Result::where(function ($query) use ($id) {
        //     $query->where('team_id_1', '=', $id)
        //     ->orWhere('team_id_2', '=', $id);
        //         })->get();

 
        $teams = Result::where(function ($query) use ($id)  {
            $query->where('team_id_1', '=', $id)
                  ->orWhere('team_id_2', '=', $id);
        })->get();

        $counter = 0;
        foreach($teams as $team){
         
            if($team->score_1!=$team->score_2){
                if($team->score_1>$team->score_2){               
                    $team_id = $team->team_id_1;
                }else{               
                    $team_id = $team->team_id_2;
                }
                if($team_id==$id){
                    $counter++;
                }
            }
        }

        
        $pair = Pair::where('team_id', $id)->get();
        
        if(!$pair->isEmpty()){
            $mortal = Mortal::find($pair[0]->mortal_id);
            Pair::find($pair[0]->id)->delete();
            $mortal->points = $mortal->points-$counter;
            $mortal->save();

            $teams = Result::where(function ($query) use ($id)  {
                $query->where('team_id_1', '=', $id)
                    ->orWhere('team_id_2', '=', $id);
            })->delete();
        }

        
       
        
        $team = Team::find($id);
        $team->delete();


    }

    public function deleteMortal(Request $request)
    {   
        
        $id = $request->id;
        $pair = Pair::where('mortal_id',$id);
        $pair->delete();
        $mortal = Mortal::find($id);
        $mortal->delete();

    }

    public function deletePair(Request $request)
    {   
        
        $id = $request->id;
        $pair = Pair::where('id', $id)->get();
        // dd($pair);
        if(!$pair->isEmpty()){
            $mortal = Mortal::find($pair[0]->mortal_id);
            $team_id = $pair[0]->team_id;
            Pair::find($pair[0]->id)->delete();

         
            $teams = Result::where(function ($query) use ($team_id)  {
                $query->where('team_id_1', '=', $team_id)
                      ->orWhere('team_id_2', '=', $team_id);
            })->get();
    
            $counter = 0;
            foreach($teams as $team){
                
                if($team->score_1!=$team->score_2){
                    if($team->score_1>$team->score_2){               
                        $team_id = $team->team_id_1;
                    }else{               
                        $team_id = $team->team_id_2;
                    }
                    if($team_id==$team_id){
                        $counter++;
                    }
                }
            }
          
            $mortal->points = $mortal->points-$counter;
            $mortal->save();

        }


    }

    public function deleteResult(Request $request)
    {   
        
        $id = $request->id;
        $result = Result::where('id',$id)->first();
        
        if($result->score_1!=$result->score_2){
            if($result->score_1>$result->score_2){               
                $team_id = $result->team_id_1;
            }else{               
                $team_id = $result->team_id_2;
            }
            $pair = Pair::where('team_id',$team_id)->first();

            if($pair){
                $mortal_id = $pair->mortal_id;
                $mortal = Mortal::find($mortal_id);
                $mortal->points = $mortal->points-1;
                $mortal->save();
            }
        }
        $result = Result::find($id)->delete();

    }
   
}
