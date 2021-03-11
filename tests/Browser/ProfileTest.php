<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\Browser\Pages\Login;
use App\User;

class ProfileTest extends DuskTestCase
{
    public function setUp(): void
    {
        $this->appDB = env('DB_DATABASE');
        parent::setUp();
        //$this->artisan('migrate:refresh');
        $this->artisan('module:seed');
    }
    
    /* @test */
    public function test_I_can_update_profile_successfully()
    {
        $user = User::where('name', 'Super Admin')->first();
        // this test the view profile functionality
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
}
