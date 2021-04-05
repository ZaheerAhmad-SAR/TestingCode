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
                ->pause(1000)
                ->type('@user-name','Amir Khan')
                ->type('@user-email','ak@qapak.org')
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

    /* @test */
    public function test_I_can_update_a_user_successfully()
    {
        $user = User::where('name', 'Super Admin')->first();
        // this test the user create functioanlity 
        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/users')
                ->assertSee('Users Details')
                ->press('@user-gear')
                ->press('@user-edit')
                ->waitForText('Edit User')
                ->click('@nav-roles')
                ->pause(1000)
                ->select('#select_roles')
                ->click('#select_roles_rightSelected')
                ->press('@add-user')
                ->assertSee('Users Details')
                ->logout();
        });
    }

    /** @test */
    public function test_i_can_invite_a_user_successfully()
    {
        $user = User::where('name', 'Super Admin')->first();
        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/users')
                ->assertSee('Users Details')
                ->click('@inviteuser')
                ->waitForText('Invite User')
                ->type('@user-invite-email','amir.khan9420@gmail.com')
                ->select('@user-invite-role')
                ->click('@user-invitate-send-button')
                ->pause(1000)
                ->assertSee('Users Details')
                ->visit('/users');
        });
    }
}
