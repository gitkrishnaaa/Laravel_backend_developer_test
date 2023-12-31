<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\UserAchievement;
use App\Models\User;
use Illuminate\Support\Str;

class AchievementUnlockedListener
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
    public function handle(AchievementUnlocked $event)
    {
        //Getting values from event

        $Achievement_name=$event->achievement;
        $user=(int)$event->user->id;

            // checking user exists . If exists update the Achievements else insert the achievements
            $exists = UserAchievement::where('user_id', $user)->exists();
            if($exists)
            {
                
                if(str::contains(strtolower($Achievement_name), 'comment'))
                {
                    $achievements=UserAchievement::where('user_id','=', $user)->value('unlockedcommentachievement');
                    if(! (str::contains(strtolower($achievements),strtolower($Achievement_name))))
                    {
                        $c_unlockedachievement=$achievements.",".$Achievement_name;
                        $updated=UserAchievement::where('user_id', $user)->update(['unlockedcommentachievement' => $c_unlockedachievement]);

                    }
                }
                if (str::contains(strtolower($Achievement_name), 'lesson'))
                {
                    $achievements=UserAchievement::where('user_id', $user)->value('unlockedlessonachievement');
                    if(! (str::contains(strtolower($achievements),strtolower($Achievement_name))))
                    {
                        $l_unlockedachievement=$achievements.",".$Achievement_name;
                        $updated=UserAchievement::where('user_id', $user)->update(['unlockedlessonachievement' => $l_unlockedachievement]);
                    }

                }
            }
            else
            {
                if (str::contains(strtolower($Achievement_name), 'comment'))
                {
                    $updated=UserAchievement::create([
                        'user_id' => $user,
                        'unlockedcommentachievement'=>$Achievement_name
                    ]);
    
                }
                if (str::contains(strtolower($Achievement_name), 'lesson'))
                {
                    $updated=UserAchievement::create([
                        'user_id' => $user,
                        'unlockedlessonachievement'=>$Achievement_name
                    ]);
                }
        
                
            }
            //Checking the current badge

            $result=UserAchievement::where('user_id', $user)->first();
            $c_achieve=count(explode(',',$result->unlockedcommentachievement));
            $l_achieve=count(explode(',',$result->unlockedlessonachievement));
            $total=$c_achieve+$l_achieve;
            if ($total==0 || $total<4)
            {
                $badge="Beginner";
            }
            elseif ($total>=4 && $total <8)
            {
                $badge= "Intermediate";
            }
            else if($total>=8 && $total <10)
            {
                $badge= "Advanced";
            }
            else if($total>=10 )
            {
                $badge= "Master";
            }

// calling badgeunlock event

            event(new \App\Events\BadgeUnlocked($badge,$event->user));

        
    }
}
