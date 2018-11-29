<?php

namespace Modules\Iperformers\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Idocs\Events\DocWasCreated;
use Modules\Idocs\Events\Handlers\SaveDocOptions;


class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        DocWasCreated::class => [
           SaveDocOptions::class,
        ]


    ];
}