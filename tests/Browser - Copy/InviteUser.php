<?php

namespace Tests\Browser;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class InviteUser extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testInviteUser()
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
