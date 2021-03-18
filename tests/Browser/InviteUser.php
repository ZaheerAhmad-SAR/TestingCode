<?php

namespace Tests\Browser;

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
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('OIRRC CAPTURE System');
        });
    }
}
