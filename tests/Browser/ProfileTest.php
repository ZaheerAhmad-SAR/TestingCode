<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\Browser\Pages\Login;
use App\User;

class ProfileTest extends DuskTestCase
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
    public function test_I_can_update_profile_successfully()
    {
        $user = User::where('name', 'Super Admin')->first();
        // this test the update profile functionality
        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/update_profile')
                    ->attach('profile_image', storage_path('dusk_testing/dusk_test_profile.png'))
                    ->select('title', 'mr')
                    ->type('name',  $user->name)
                    /* this field is disabled at front end
                    ->type('email',  $user->email)
                    */
                    ->type('phone', '123456789')
                    ->type('password', 'at@m|c_en@rgy1272')
                    ->type('password_confirmation', 'at@m|c_en@rgy1272')
                    ->waitFor('.emailChecked')
                    ->check('notification_type')
                    ->radio('bug', '1')
                    ->radio('form', '1')
                    ->radio('subject', '1')
                    ->check('show_signature_pad')
                    ->press('Save Changes')
                    ->assertSee('Update Profile')
                    ->logout();
        });
    }

    /* @test */
    public function test_I_can_update_user_preferences_successfully()
    {
        $user = User::where('name', 'Super Admin')->first();
        // this test the user preferences functionality
        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/home/user_preferences')
                    ->radio('default_theme', 'light')
                    ->select('default_pagination', '20')
                    ->press('Update Changes')
                    ->assertSee('Super Admin Prefrences')
                    ->logout();
        });
    }
}
