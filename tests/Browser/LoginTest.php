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
        $this->appUrl = env('APP_URL');
        parent::setUp();
        //$this->artisan('migrate:refresh');
        $this->artisan('module:seed');
    }

    /* @test */
    public function test_I_can_login_successfully()
    {

        $this->browse(function ($browser) {
            $browser->visit('/login')
                    ->type('email', 'superadmin@admin.com')
                    ->type('password', 'at@m|c_en@rgy1272')
                    ->press('Sign In')
                    ->assertSee('Please add study admin role first');
        });
    }
}
