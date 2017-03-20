<?php

namespace Spatie\DbSnapshots;

use Illuminate\Contracts\Filesystem\Filesystem as Disk;
use Illuminate\Support\Collection;

class SnapshotRepository
{
    /** @var \Illuminate\Filesystem\FilesystemAdapter */
    protected $disk;

    public function __construct(Disk $disk)
    {
        $this->disk = $disk;
    }

    public function getAll(): Collection
    {
        return collect($this->disk->allFiles())
            ->map(function (string $fileName) {
                return new Snapshot($this->disk, $fileName);
            })
            ->sortByDesc(function(Snapshot $snapshot) {
                return $snapshot->createdAt()->toDateTimeString();
            });
    }

    public function getByName(string $name)
    {
        return $this->getAll()->first(function (Snapshot $snapshot) use ($name) {
             return $snapshot->name === $name;
        });
    }
}