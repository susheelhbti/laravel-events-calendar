<?php

namespace DavideCasiraghi\LaravelEventsCalendar\Tests;

use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Testing\WithFaker;
use DavideCasiraghi\LaravelEventsCalendar\Models\Country;
use DavideCasiraghi\LaravelEventsCalendar\Models\Continent;
use DavideCasiraghi\LaravelEventsCalendar\Models\EventVenue;
use DavideCasiraghi\LaravelEventsCalendar\Http\Controllers\EventVenueController;

class EventVenueControllerTest extends TestCase
{
    use WithFaker;

    /***************************************************************/

    /** @test */
    public function it_displays_the_event_venues_index_page()
    {
        // Authenticate the admin
        //$this->authenticateAsAdmin();

        $this->get('eventVenues')
            ->assertViewIs('laravel-events-calendar::eventVenues.index')
            ->assertStatus(200);
    }

    /** @test */
    public function it_displays_the_event_venue_create_page()
    {
        $this->get('eventVenues/create')
            ->assertViewIs('laravel-events-calendar::eventVenues.create')
            ->assertStatus(200);
    }

    /** @test */
    public function it_stores_a_valid_event_venue()
    {
        $attributes = factory(EventVenue::class)->raw();

        Continent::insert([
            'name' => 'Europe',
            'code' => 'EU',
        ]);
        Country::insert([
            'name' => 'Italy',
            'code' => 'IT',
            'continent_id' => 1,
        ]);

        $user = User::first();
        auth()->login($user);

        $response = $this->post('/eventVenues', $attributes);
        $eventVenue = EventVenue::first();

        //$this->assertDatabaseHas('organizers', $attributes);
        $response->assertRedirect('/eventVenues/');
    }

    /** @test */
    public function it_does_not_store_invalid_event_venue()
    {
        $response = $this->post('/eventVenues', []);
        $response->assertSessionHasErrors();
        $this->assertNull(EventVenue::first());
    }

    /** @test */
    public function it_displays_the_event_venue_show_page()
    {
        Continent::insert([
            'name' => 'Europe',
            'code' => 'EU',
        ]);
        Country::insert([
            'name' => 'Italy',
            'code' => 'IT',
            'continent_id' => 1,
        ]);

        $eventVenue = factory(EventVenue::class)->create();
        $response = $this->get("/eventVenues/{$eventVenue->id}");
        $response->assertViewIs('laravel-events-calendar::eventVenues.show')
                 ->assertStatus(200);
    }

    /** @test */
    public function it_displays_the_event_venue_edit_page()
    {
        Continent::insert([
            'name' => 'Europe',
            'code' => 'EU',
        ]);
        Country::insert([
            'name' => 'Italy',
            'code' => 'IT',
            'continent_id' => 1,
        ]);

        $user = User::first();
        auth()->login($user);

        $eventVenue = factory(EventVenue::class)->create();
        $response = $this->get("/eventVenues/{$eventVenue->id}/edit");
        $response->assertViewIs('laravel-events-calendar::eventVenues.edit')
                 ->assertStatus(200);
    }

    /* @test */
    /*public function it_updates_valid_event_venue()
    {
        // https://www.neontsunami.com/posts/scaffolding-laravel-tests
        $eventVenue = factory(EventVenue::class)->create();
        $attributes = factory(EventVenue::class)->raw(['name' => 'Updated']);
        $response = $this->put("/eventVenues/{$eventVenue->id}", $attributes);
        $response->assertRedirect('/eventVenues/');
        $this->assertEquals('Updated', $eventVenue->fresh()->name);
    }*/

    /* @test */
    /*public function it_does_not_update_invalid_event_venue()
    {
        $eventVenue = factory(EventVenue::class)->create(['name' => 'Example']);
        $response = $this->put("/eventVenues/{$eventVenue->id}", []);
        $response->assertSessionHasErrors();
        $this->assertEquals('Example', $eventVenue->fresh()->name);
    }*/

    /* @test */
    /*public function it_deletes_event_venues()
    {
        $eventVenue = factory(EventVenue::class)->create();
        $response = $this->delete("/eventVenues/{$eventVenue->id}");
        $response->assertRedirect('/eventVenues');
        $this->assertNull($eventVenue->fresh());
    }*/

    /* @test */
    /*public function it_store_from_event_venue_modal()
    {
        $request = new \Illuminate\Http\Request();

        $description = $this->faker->paragraph;
        $data = [
            'name' => $this->faker->name,
            'website' => $this->faker->url,
            'description' => $description,
            'email' => $this->faker->email,
            'phone' => $this->faker->e164PhoneNumber,
        ];

        $request->replace($data);

        $eventVenueController = new EventVenueController();
        $eventVenueController->storeFromModal($request);

        $data['description'] = clean($description);
        $this->assertDatabaseHas('event_venues', $data);
    }*/
}