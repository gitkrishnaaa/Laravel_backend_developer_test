<?php

namespace App\Listeners;

use App\Events\CommentWritten;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
//use App\Models\UserAchievement;

class CommentAchievement
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
    public function handle(CommentWritten $event)
    {
       //Inserting comments fired from the event.

        $created_at=Carbon::now()->toDateTimeString();
        $updated_at=Carbon::now()->toDateTimeString();
        $comment=$event->comment->body;
        $comments=$event->comment;
        $user_id=$event->comment->user_id;
        $comments->create([
                'body'=>$comment,
                'user_id'=>$user_id,
                'created_at'=>$created_at,
                'updated_at'=>$created_at
        ]);

        // If the user exist it checking for comment achievement.
            $user=User::find($user_id);
            if($user)
            {
                $comment_num=Comment::where('user_id','=',$user_id)->count();
                if($comment_num>=1 && $comment_num<3)
                {
                    $achievement="First Comment Written ";
                }
                elseif($comment_num>=3 && $comment_num <5){
                    $achievement= "3 Comments Written";
                }
                else if($comment_num>=5 && $comment_num <10)
                {
                    $achievement= "5 Comments Written";
                }
                else if($comment_num>=10 && $comment_num <20)
                {
                    $achievement= "10 Comments Written";
                }
                else if($comment_num>=20)
                {
                    $achievement= "20 Comments Written";
                } 

                //Calling Achievement unlock event.
                
                event(new \App\Events\AchievementUnlocked($achievement,$user));
            }
            else
                return "user does not exist";
    }
}
