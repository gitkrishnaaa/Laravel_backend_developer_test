<?php

namespace Tests\Unit;

//use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use App\Listeners\CommentAchievement;
use App\Events\CommentWritten;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Events\AchievementUnlocked;
use Illuminate\Support\Testing\Fakes\EventFake;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\Factory;

class CommentWrittenTest extends \Tests\TestCase
{
    /**
     * A basic unit test example.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Ensure the event dispatcher is set up
        Event::fake(CommentWritten::class);
    }

    use RefreshDatabase;
    public function testHandle()
    {
        // Mock necessary dependencies
        $fake =Event::fake(CommentWritten::class);
        
        
        $comment = Comment::factory()->create();
        
        // Create an instance of the listener
        $listener = new CommentAchievement();

        // Create an instance of the event
        $event = new CommentWritten($comment);

        // Call the handle method of the listener
        $listener->handle($event);

        // Add assertions based on the expected behavior of the listener
        // For example, assert that the CommentAchievement logic is executed correctly

        // Assert that the event was dispatched

        Event::assertListening(CommentWritten::class,CommentAchievement::class);
        $this->assertDatabaseHas('user_achievements',['user_id'=>$comment->user_id,'unlockedcommentachievement'=>'First Comment Written']);

    }
}

