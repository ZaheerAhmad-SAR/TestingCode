<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\User;

class UserTest extends DuskTestCase
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
    public function test_I_can_create_a_user_successfully()
    {
        $user = User::where('name', 'Super Admin')->first();
        // this test the user create functioanlity 
        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/users')
                ->assertSee('Users Details')
                ->press('Add User')
                ->waitForText('Add User')
                ->assertSee('Add User')
                ->pause(2000)
                ->type('@user-name','Amir Khan')
                ->value('@user-email','ak@qapak.org')
                ->type('password','At@m|c_en@rgy1272')
                ->type('password_confirmation','At@m|c_en@rgy1272')
                ->click('@nav-roles')
                ->pause(1000)
                ->select('#select_roles')
                ->click('#select_roles_rightSelected')
                ->press('@add-user')
                ->assertSee('Users Details')
                ->logout();
        });
    }

    // Test for invite a user

    public function test_system_admin_can_invite_a_user()
    {
        $user = User::where('name', 'Super Admin')->first();
        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/ocap_new/users')
                ->assertSee('Users Details')
                ->click('@inviteuser')
                ->waitFor('#inviteuser')
                ->assertVisible('#inviteuser')
                ->assertSee('Invite User')
                ->type('email','john@oirrc.net')
                ->select('roles','Study Admin')
                ->click('@send_invitation')
                ->assertSee('Users Details')
                ->visit('/users');
        });
    }


}
