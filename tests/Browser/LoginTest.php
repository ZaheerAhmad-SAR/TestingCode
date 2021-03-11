<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\User;
use Illuminate\Database\Seeder;

class LoginTest extends DuskTestCase
{

   public function setUp(): void
    {
        $this->appDB = env('DB_DATABASE');
        parent::setUp();
        //$this->artisan('migrate:fresh');
        $this->artisan('module:seed');
    }

    /* @test */
    public function test_I_can_login_successfully()
    {
        $user = User::where('name', 'Super Admin')->first();
        // this test the login functionality
        $this->browse(function ($browser) use ($user) {
            $browser->visit('/login')
                    ->type('email',  $user->email)
                    ->type('password', 'at@m|c_en@rgy1272')
                    ->press('Sign In')
                    ->assertSee('Please add study admin role first')
                    ->logout();
        });
    }

}
