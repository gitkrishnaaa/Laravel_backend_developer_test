<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserAchievement;
use Illuminate\Http\Request;
use App\Events\AchievementUnlocked;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB; 
use App\Models\Comment;
use App\Models\Lesson;

class AchievementsController extends Controller
{
    public function index(User $user)
    {
        //checking user exists. If exists getting details from user_achievements
        $users=User::find($user);
        if($users)
        {

            $result= DB::table('user_achievements')->where('user_id','=', $user)->first();
            $unlocked_achievements=array();
            $currentbadge="Beginner";
            $c_unlock="None";
            $l_unlock="None";
            if($result)
            {
                $unblocked_achievements_all=$result->unlockedcommentachievement.",".$result->unlockedlessonachievement;
                $unlocked_achievements=explode(',',$unblocked_achievements_all);
                $c_achieve=explode(',',$result->unlockedcommentachievement);
                $currentbadge=$result->currentbadge;
                $c_achieve=explode(',',$result->unlockedcommentachievement);
                $c_unlock=end($c_achieve);
                $l_achieve=explode(',',$result->unlockedlessonachievement);
                $l_unlock=end( $l_achieve);
            }
            if( $c_unlock==""){
                $c_unlock="None"; 
            }
            if( $l_unlock==""){
                $l_unlock="None"; 
            }
            if( $currentbadge==""){
                $currentbadge="Beginner"; 
            }

            //getting details from the achievement config folder

            $next_available_c_achievements=config('Achievements.NEXT_COMMENT.'.$c_unlock);
            $next_available_l_achievements=config('Achievements.NEXT_LESSON.'. $l_unlock);
            $next_badge=config('Achievements.NEXT_BADGE.'. $currentbadge);
            $remaining=config('Achievements.NEXT_BADGE_POINT.'. $currentbadge);
            $next_available_achievements=array($next_available_c_achievements,$next_available_l_achievements);

            return response()->json([
                'unlocked_achievements' =>$unlocked_achievements,
                'next_available_achievements' => $next_available_achievements,
                'current_badge' => $currentbadge,
                'next_badge' => $next_badge,
                'remaing_to_unlock_next_badge' => $remaining
            ]);

        }
        else{
            return response->json(["message"=>"user does not exist"]);
        }
        
    }
   
}
