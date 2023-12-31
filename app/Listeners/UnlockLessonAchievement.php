<?php

namespace App\Listeners;

use App\Events\LessonWatched;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use App\Models\LessonUser;
use App\Models\User;
use App\Models\UserAchievement;
use App\Models\Lesson;

class UnlockLessonAchievement
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(LessonWatched $event)
    {
        // Adding the lesson to the lesson_user table if the user exist.
        $user=User::find($event->user->id);
        if($user)
        {
            $user_id=$event->user->id;
            $lesson=$event->lesson->id;
            DB::table('lesson_user')->insert([
                'lesson_id'=>$lesson,
                'user_id'=>$user_id,
                'watched'=> true
            ]);
            
            // Checking the lesson Achievement.

                $lesson_num=DB::table('lesson_user')->where('user_id','=',$user_id)->where('watched','=',true)->count();
                if($lesson_num==1 || $lesson_num<5)
                {
                    $achievement="First Lesson Watched";
                }
                elseif($lesson_num>=5 && $lesson_num <10){
                    $achievement= "5 Lessons Watched";
                }
                else if($lesson_num>=10 && $lesson_num <25)
                {
                    $achievement= "c";
                }
                else if($lesson_num>=25 && $lesson_num<50)
                {
                    $achievement= "25 Lessons Watched";
                }
                else if($lesson_num>=50)
                {
                    $achievement= "50 Lessons Watched";
                } 

                //Calling achievementunlock event

                event(new \App\Events\AchievementUnlocked($achievement,$user));
        }
        else
        {
            return "User does not exist";
        }
        
    }
}
