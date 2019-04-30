<?php

namespace DavideCasiraghi\LaravelEventsCalendar\Tests;

use Carbon\Carbon;
use DavideCasiraghi\LaravelEventsCalendar\Facades\LaravelEventsCalendar;
use DavideCasiraghi\LaravelEventsCalendar\LaravelEventsCalendarServiceProvider;
use DavideCasiraghi\LaravelEventsCalendar\Models\Teacher;
use Illuminate\Foundation\Testing\WithFaker;
//use DavideCasiraghi\LaravelEventsCalendar\Http\Controllers\JumbotronImageController;

class TeacherControllerTest extends TestCase
{
    use WithFaker;
    
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->loadLaravelMigrations(['--database' => 'testbench']);
        //$this->withFactories(__DIR__.'/database/factories');
        
        //$this->artisan('db:seed', ['--class' => 'ContinentsTableSeeder']);
        //$this->artisan('db:seed', ['--database'=>'testbench','--class'=>'ContinentsTableSeeder']);
    
        //$this->artisan('db:seed', ['--database'=>'testbench','--class'=>'LaravelEventsCalendar\\LaravelEventsCalendar\\ContinentsTableSeeder']);
        //$this->artisan('db:seed', ['--database'=>'testbench','--class'=>'ContinentsTableSeeder', '--path'=>'/database/seeds/']);
        //$this->seed('ContinentsTableSeeder');
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelEventsCalendarServiceProvider::class,
            \Mews\Purifier\PurifierServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'LaravelEventsCalendar' => LaravelEventsCalendar::class, // facade called PhpResponsiveQuote and the name of the facade class
            'Purifier' => \Mews\Purifier\Facades\Purifier::class,
        ];
    }

    /***************************************************************/

    /** @test */
    public function the_route_teacher_index_can_be_accessed()
    {
        // Authenticate the admin
        //$this->authenticateAsAdmin();
        
        $this->get('teachers')
            ->assertViewIs('laravel-events-calendar::teachers.index')
            ->assertStatus(200);
    }

    /** @test */
    public function the_route_teacher_create_can_be_accessed()
    {
        $this->get('teachers/create')
            ->assertViewIs('laravel-events-calendar::teachers.create')
            ->assertStatus(200);
    }
    
    /** @test */
    public function the_route_teacher_store_can_be_accessed()
    {
        /*$data = [
            'title' => 'test title',
            'body' => 'test body',
            'button_text' => 'test button text',
            'button_url' => 'test button url',
            'image_file_name' => 'test.jpg',
        ];*/
        
        $bio = $this->faker->paragraph;
        $data = [
                'name' => $this->faker->name,
                'bio' => $bio,
                'year_starting_practice' => '2000',
                'year_starting_teach' => '2006',
                'significant_teachers' => $this->faker->paragraph,
                'website' => $this->faker->url,
                'facebook' => 'https://www.facebook.com/'.$this->faker->word,
                'country_id' => $this->faker->numberBetween($min = 1, $max = 253),
            ];
        $response = $this
                            ->followingRedirects()
                            ->post('/teachers', $data);

        // Assert in database
        $data['bio'] = clean($bio);
        //$this->assertDatabaseHas('teachers', $data);
        
        

        $this
            ->followingRedirects()
            ->post('/teachers', $data)->dump();

        //$this->assertDatabaseHas('teachers', $data);
    }
    
    
    /** @test */
    /*public function the_route_teacher_destroy_can_be_accessed()
    {
        $id = Teacher::insertGetId([
            'image_file_name' => 'test image name',
            'button_url' => 'test button url',
        ]);

        JumbotronImageTranslation::insert([
            'jumbotron_image_id' => $id,
            'title' => 'test title',
            'body' => 'test body',
            'button_text' => 'test button text',
            'locale' => 'en',
        ]);

        $this->delete('jumbotron-images/1')
            ->assertStatus(302);
    }*/
    
    
    /**
     * Test that logged user can create a teacher.
     */
    /*public function test_a_logged_user_can_create_teacher()
    {
        // Authenticate the user
        //$this->authenticate();

        // Post datas to create teacher (we don't include created_by and slug becayse are generated by the store method )
        $bio = $this->faker->paragraph;
        $data = [
                'name' => $this->faker->name,
                'bio' => $bio,
                'year_starting_practice' => '2000',
                'year_starting_teach' => '2006',
                'significant_teachers' => $this->faker->paragraph,
                'website' => $this->faker->url,
                'facebook' => 'https://www.facebook.com/'.$this->faker->word,
                'country_id' => $this->faker->numberBetween($min = 1, $max = 253),
            ];
        $response = $this
                            ->followingRedirects()
                            ->post('/teachers', $data);

        // Assert in database
        $data['bio'] = clean($bio);
        $this->assertDatabaseHas('teachers', $data);

        // Status
        $response
                    ->assertStatus(200)
                    ->assertSee(__('messages.teacher_added_successfully'));
    }*/

}
