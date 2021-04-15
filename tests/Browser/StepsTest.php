<?php
namespace Tests\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\User;
use Tests\Browser\Pages\Modality;

class StepsTest extends DuskTestCase
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
    public function test_I_can_create_a_step_successfully()
    {
        $user = User::where('name', 'Super Admin')->first();
        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/studies')
                ->assertSee('Studies')
                ->click('@study_title_dusk')
                ->pause(2000)
                ->click('@study_tools')
                ->pause(1000)
                ->click('@study_design')
                ->pause(1000)
                ->click('@study_structure')
                ->assertSee('Visits & Modalities Sections')
                ->pause(1000)
                ->click('@add_steps')
                ->waitForText('Add a steps')
                ->type('step_position', '1')
                ->select('phase_id', '68bff15a-43b3-42ef-b9b1-b80ca6d854aa')
                ->select('form_type_id', '1')
                ->select('modility_id', 'c7230c04-077b-477d-9e60-b490169c69e2')
                ->type('step_name', 'step-dusk')
                ->type('step_description', '2')
                ->select('graders_number', '1')
                ->pause(1000)
                ->click('@saveSteps');
        });
    }

    /* @test */
    public function test_I_can_update_a_step_successfully()
    {
        $user = User::where('name', 'Super Admin')->first();
        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/studies')
                ->assertSee('Studies')
                ->click('@study_title_dusk')
                ->pause(2000)
                ->click('@study_tools')
                ->pause(1000)
                ->click('@study_design')
                ->pause(1000)
                ->click('@study_structure')
                ->assertSee('Visits & Modalities Sections')
                ->pause(1000)
                ->click('@step_actions')
                ->click('@edit_steps_dusk')
                ->waitForText('Edit a Step')
                ->type('step_position', '1')
                ->select('phase_id', '68bff15a-43b3-42ef-b9b1-b80ca6d854aa')
                ->select('form_type_id', '1')
                ->select('modility_id', 'c7230c04-077b-477d-9e60-b490169c69e2')
                ->type('step_name', 'step-dusk-update')
                ->type('step_description', 'Descriptions to get update')
                ->select('graders_number', '1')
                ->pause(1000)
                ->click('@saveSteps');
        });
    }

    /* @test */
    public function test_I_can_delete_a_step_successfully()
    {
        $user = User::where('name', 'Super Admin')->first();
        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/studies')
                ->assertSee('Studies')
                ->click('@study_title_dusk')
                ->pause(2000)
                ->click('@study_tools')
                ->pause(1000)
                ->click('@study_design')
                ->pause(1000)
                ->click('@study_structure')
                ->assertSee('Visits & Modalities Sections')
                ->pause(1000)
                ->click('@step_actions')
                ->click('@delete_steps_dusk')
                ->assertDialogOpened('Are you sure to delete?')
                ->acceptDialog('press OK')
                ->pause(2000);
                
        });
    }
     /* @test */
    public function test_I_can_clone_a_step_successfully()
    {
        $user = User::where('name', 'Super Admin')->first();
        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/studies')
                ->assertSee('Studies')
                ->click('@study_title_dusk')
                ->pause(2000)
                ->click('@study_tools')
                ->pause(1000)
                ->click('@study_design')
                ->pause(1000)
                ->click('@study_structure')
                ->assertSee('Visits & Modalities Sections')
                ->pause(1000)
                ->click('@step_actions')
                ->click('@clone_steps_dusk')
                ->waitForText('Clone step / form to other phases / visits')
                ->check('@phase_checkbox_clone')
                ->click('@Clonestep');
        });
    }
    
}
