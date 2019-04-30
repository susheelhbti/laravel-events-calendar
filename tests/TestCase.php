<?php

namespace DavideCasiraghi\LaravelEventsCalendar\Tests;

//use DavideCasiraghi\LaravelEventsCalendar\Models\User;
//use Illuminate\Foundation\Auth\User;
use Orchestra\Testbench\TestCase as BaseTestCase;
use DavideCasiraghi\LaravelEventsCalendar\Models\User;

//use Illuminate\Foundation\Testing\TestCase;

abstract class TestCase extends BaseTestCase
{
    //use CreatesApplication;

    // Authenticate the user
    public function authenticate()
    {
        $user = factory(User::class)->make();
        $this->actingAs($user);
    }

    // Authenticate the admin
    public function authenticateAsAdmin()
    {
        $user = factory(User::class)->make([
                'group' => 2,
            ]);

        $this->actingAs($user);
    }

    // Authenticate the super admin
    public function authenticateAsSuperAdmin()
    {
        $user = factory(User::class)->make([
                'group' => 1,
            ]);

        $this->actingAs($user);
    }
}