<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\User;
use Illuminate\Database\Seeder;

class LoginTest extends DuskTestCase
{
    /* for running migration and seeding only once for all test cases in this class */
    protected static $migrationRun = false;

    public function setUp(): void {
        parent::setUp();

        if(!static::$migrationRun) {
            $this->appDB = env('DB_DATABASE');
            //$this->artisan('migrate:fresh');
            $this->artisan('module:seed');
            static::$migrationRun = true;
        }
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

    /* @test */
    public function test_I_can_login_with_wrong_credientials_successfully()
    {
        // this test the login functionality with wrong credientials
        $this->browse(function ($browser) {
            $browser->visit('/login')
                    ->type('email',  'ak@qapak.org')
                    ->type('password', 'wrong_password')
                    ->press('Sign In')
                    ->assertSee('These credentials do not match our records.');
        });
    }

    /* @test */
    public function test_I_can_forget_password_successfully()
    {
        // this test the forget password functionality
        $this->browse(function ($browser) {
            $browser->visit('/password/reset')
                    ->type('email',  'ak@qapak.org')
                    ->press('Send Link')
                    ->assertSee("We have e-mailed your password reset link!");
        });
    }

    /* @test */
    public function test_I_can_forget_password_with_wrong_email_successfully()
    {
        // this test the forget password functionality with wrong email credientials
        $this->browse(function ($browser) {
            $browser->visit('/password/reset')
                    ->type('email',  'test@qapak.org')
                    ->press('Send Link')
                    ->assertSee('We can\'t find a user with that e-mail address.');
        });
    }

}
