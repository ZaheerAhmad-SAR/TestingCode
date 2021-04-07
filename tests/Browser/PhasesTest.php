<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\User;
use Tests\Browser\Pages\Modality;

class PhasesTest extends DuskTestCase
{
    /* for running migration and seeding only once for all test cases in this class */

    public function setUp(): void
   {
       $this->appUrl = env('APP_URL');
       parent::setUp();
       //$this->artisan('migrate:refresh');
       $this->artisan('module:seed');
   }

    /* @test */
    public function test_I_can_create_a_phase_successfully()
    {
        $user = User::where('name', 'Super Admin')->first();
        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/ocap_new/studies')
                ->assertSee('Studies')
                ->pause(5000);
        });
    }

}
