<?php

namespace Spatie\DbSnapshots\Commands\Test;

use Illuminate\Support\Facades\Artisan;
use Spatie\DbSnapshots\Test\TestCase;
use Mockery as m;

class DeleteTest extends TestCase
{
    /** @var \Spatie\DbSnapshots\Commands\Delete|m\Mock */
    protected $command;

    public function setUp()
    {
        parent::setUp();

        $this->command = m::mock('Spatie\DbSnapshots\Commands\Create[choice]');

        $this->app->bind('command.monitor:create', function () {
            return $this->command;
        });
    }

    /** @test */
    public function it_can_delete_a_snapshot()
    {
        $this->disk->assertExists('snapshot2.sql');

        $this->command
            ->shouldReceive('confirm')
            ->once()
            ->with('/Which snapshot/')
            ->andReturn('snapshot2');

        Artisan::call('snapshots:delete');

        $this->disk->assertMissing('snapshot2.sql');
    }

    /** @test */
    public function it_can_delete_a_snapshot_with_a_specific_name()
    {
        $this->disk->assertExists('snapshot2.sql');

        Artisan::call('snapshots:delete', ['name' => 'snapshot2']);

        $this->disk->assertMissing('snapshot2.sql');
    }
}