<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Listeners\UnlockLessonAchievement;
use App\Events\LessonWatched;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Events\AchievementUnlocked;
use Illuminate\Support\Testing\Fakes\EventFake;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\Factory;

class LessonWatchedTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Ensure the event dispatcher is set up
        Event::fake(LessonWatched::class);
    }
use RefreshDatabase;

    public function testHandle()
    {
        // Mock necessary dependencies
        $fake =Event::fake(LessonWatched::class);
        $user = User::factory()->create();
        
        $lesson = Lesson::factory()->create();

        // Create an instance of the listener
        $listener = new UnlockLessonAchievement();

        // Create an instance of the event
        $event = new LessonWatched($lesson,$user);

        // Call the handle method of the listener
        $listener->handle($event);

        // Add assertions based on the expected behavior of the listener
        // For example, assert that the CommentAchievement logic is executed correctly

        // Assert that the event was dispatched

        Event::assertListening(LessonWatched::class,UnlockLessonAchievement::class);
        $this->assertDatabaseHas('user_achievements',['user_id'=>$user->id,'unlockedlessonachievement'=>'First Lesson Watched']);

    }
}
