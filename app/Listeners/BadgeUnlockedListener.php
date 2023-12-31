<?php

namespace App\Listeners;

use App\Events\BadgeUnlocked;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;
use App\Models\UserAchievement;

class BadgeUnlockedListener
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
    public function handle(BadgeUnlocked $event): void
    {

        // Getting bade name and user form event.
        
            $badge_name=$event->badge;
            $user=$event->user->id;
//Checks user exist in the table . If exists updating the current badge else inserting it

            $exists = UserAchievement::where('user_id', $user)->exists();
            if($exists)
            {
                $updated=UserAchievement::where('user_id', $user)->update(['currentbadge' => $badge_name]);
            }
            else
            {
                UserAchievement::create([
                    'user_id' => $user,
                    'currentbadge' => $badge
                ]);

            }

    }
}
