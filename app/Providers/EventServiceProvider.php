<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        App\Events\AchievementUnlocked::class=> [
            App\Listeners\AchievementUnlockedListener::class

        ],
        App\Events\Badgeunlocked::class=> [
            App\Listeners\BadgeUnlockedListener::class

        ],
        App\Events\CommentWritten::class=> [
            App\Listeners\CommentAchievement::class

        ],
        App\Events\LessonWatch::class=> [
            App\Listeners\UnlockLessonAchievement::class

        ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
       

    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return true;
    }
}
