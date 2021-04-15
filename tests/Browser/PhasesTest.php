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
                ->visit('/studies')
                ->assertSee('Studies')
                ->click('@study_title_dusk')
                ->pause(3000)
                ->click('@study_tools')
                ->pause(2000)
                ->click('@study_design')
                ->pause(2000)
                ->click('@study_structure')
                ->assertSee('Visits & Modalities Sections')
                ->pause(2000)
                ->click('@add_phase')
                ->waitForText('Add a Phase')
                ->type('position', '1')
                ->type('name', 'Day 1')
                ->type('duration', '3')
                ->type('window', '2')
                ->radio('is_repeatable', '0')
                ->pause(2000)
                ->click('@savePhase');
        });
    }

     /* @test */
    public function test_I_can_create_a_phase_update_successfully()
    {
        $user = User::where('name', 'Super Admin')->first();
        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/studies')
                ->assertSee('Studies')
                ->click('@study_title_dusk')
                ->pause(3000)
                ->click('@study_tools')
                ->pause(2000)
                ->click('@study_design')
                ->pause(2000)
                ->click('@study_structure')
                ->assertSee('Visits & Modalities Sections')
                ->pause(2000)
                ->click('@update_phase')
                ->click('@edit_phase_dusk')
                ->waitForText('Edit a Phase')
                ->type('position', '3')
                ->type('name', 'Day 1 update')
                ->type('duration', '4')
                ->type('window', '3')
                ->radio('is_repeatable', '0')
                ->pause(2000)
                ->click('@savePhase');
        });
    }

    public function test_I_can_clone_a_phase_successfully()
    {
        $user = User::where('name', 'Super Admin')->first();
        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/studies')
                ->assertSee('Studies')
                ->click('@study_title_dusk')
                ->pause(3000)
                ->click('@study_tools')
                ->pause(2000)
                ->click('@study_design')
                ->pause(2000)
                ->click('@study_structure')
                ->assertSee('Visits & Modalities Sections')
                ->pause(2000)
                ->click('@update_phase')
                ->click('@clone_phase_dusk')
                ->waitForText('Clone Phase with Same Name / Different Name')
                ->type('@clone_position', '5')
                ->type('@phase_name_clone', 'Screenning-clone')
                ->pause(2000)
                ->click('@phase_clone_submit');
        });
    }

    public function test_I_can_delete_a_phase_successfully()
    {
        $user = User::where('name', 'Super Admin')->first();
        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/studies')
                ->assertSee('Studies')
                ->click('@study_title_dusk')
                ->pause(3000)
                ->click('@study_tools')
                ->pause(2000)
                ->click('@study_design')
                ->pause(2000)
                ->click('@study_structure')
                ->assertSee('Visits & Modalities Sections')
                ->pause(2000)
                ->click('@update_phase')
                ->click('@deletePhase_dusk')
                ->assertDialogOpened('Are you sure to delete?')
                ->acceptDialog('press OK');
                
        });
    }
}
