<?php

namespace Spatie\DbSnapshots\Commands\Test;

use Spatie\DbSnapshots\Events\CreatedSnapshot;
use Spatie\DbSnapshots\Test\TestCase;
use Event;
use Artisan;

class CreatedSnapshotTest extends TestCase
{
    /** @test */
    public function after_the_snapshot_has_been_created_the_created_snapshot_event_will_be_fired()
    {
        Event::fake();

        Artisan::call('snapshots:create', ['name' => 'my-snapshot']);

        Event::assertDispatched(CreatedSnapshot::class, function(CreatedSnapshot $event) {
            return $event->snapshot->fileName === 'my-snapshot.sql';
        });
    }
}