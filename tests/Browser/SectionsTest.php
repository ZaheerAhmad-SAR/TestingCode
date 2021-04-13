<?php
namespace Tests\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\User;
use Tests\Browser\Pages\Modality;

class SectionsTest extends DuskTestCase
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
    public function test_I_can_add_a_section_successfully()
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
                ->click('@addsection-dusk')
                ->waitForText('Add a section')
                ->type('sec_name','testing dusk')
                ->type('sec_description','testing description dusk')
                ->type('sort_num','1')
                ->click('@Save_section')
                ->pause(3000);
        });
    }

    public function test_I_can_update_a_section_successfully()
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
                ->click('@addsection-dusk')
                ->waitForText('Add a section')
                ->click('@edit_sec_dusk')
                ->waitForText('Edit a section')
                ->type('sec_name','testing dusk up')
                ->type('sec_description','testing description dusk up')
                ->type('sort_num','2')
                ->click('@Save_section')
                ->pause(3000);
        });
    }
    // ->click('@add_steps')
    // ->waitForText('Add a steps')
    // ->type('step_position', '1')
    // ->select('phase_id', '68bff15a-43b3-42ef-b9b1-b80ca6d854aa')
    // ->select('form_type_id', '1')
    // ->select('modility_id', 'c7230c04-077b-477d-9e60-b490169c69e2')
    // ->type('step_name', 'step-dusk')
    // ->type('step_description', '2')
    // ->select('graders_number', '1')
    // ->pause(1000)
    // ->click('@saveSteps');
}
