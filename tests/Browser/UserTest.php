<?php

namespace Tests\Browser;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UserTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    // Test for Create a User
    public function test_system_admin_can_create_a_user()
    {
        $user = User::where('name', 'Super Admin')->first();
        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/users')
                ->assertSee('Users Details')
                ->click('@add_user')
                ->waitFor('#createUser')
                ->assertVisible('#createUser')
                ->assertSee('Add User')
                ->waitFor('#userFormInner')
                ->assertVisible('#userFormInner')
                ->type('name','JS Developer')
                ->type('email','john@oirrc.net')
                ->type('password','At@m|c_en@rgy1272')
                ->type('password_confirmation','At@m|c_en@rgy1272')
//                ->click('@nav-Modalities')
//                ->waitFor('#nav-Modalities')
//                ->assertVisible('#nav-Modalities')
//                ->assertSee('Select Roles')
                ->pause(5000);
        });
    }

    // Test for invite a user

    public function test_system_admin_can_invite_a_user()
    {
        $user = User::where('name', 'Super Admin')->first();
        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/users')
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
