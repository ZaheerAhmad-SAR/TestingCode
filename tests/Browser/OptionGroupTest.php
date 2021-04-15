<?php

namespace Tests\Browser;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class OptionGroupTest extends DuskTestCase
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
    public function test_I_can_create_a_option_group_successfully()
    {
        $user = User::where('name', 'Super Admin')->first();
        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/studies')
                ->assertSee('Studies')
                ->click('@study_title_dusk')
                ->pause(1000)
                ->click('@study_tools')
                ->pause(1000)
                ->click('@study_design')
                ->pause(1000)
                ->click('@options-group')
                ->assertSee('Option Groups')
                ->pause(1000)
                ->click('@addOptionGroups')
                ->waitForText('Add Option Group')
                ->type('@group-name', 'Group 1443 ')
                ->type('@group-description', 'Group Description')
                ->radio('option_layout', 'horizontal')
                ->click('@add-option-group')
                ->waitFor('.appendDataOptions')
                ->assertVisible('.appendDataOptions')
                ->type('@option_name','Week')
                ->type('@option_value','1')
                ->click('@option-group-save-button')
                ->logout();
        });
    }

    /* @test */
    public function test_I_can_update_a_option_group_successfully()
    {
        $user = User::where('name', 'Super Admin')->first();
        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/studies')
                ->assertSee('Studies')
                ->click('@study_title_dusk')
                ->pause(1000)
                ->click('@study_tools')
                ->pause(1000)
                ->click('@study_design')
                ->pause(1000)
                ->click('@options-group')
                ->assertSee('Option Groups')
                ->pause(1000)
                ->press('@optionGroup-navtab')
                ->press('@optionGroup-edit')
                ->waitForText('Edit Option Group')
                ->type('@option_group_name_edit', 'Overall Gradability (GRADABLE/PARTIALLY GRADABLE/UNGRADABLE) 2021')
                ->click('@option-group-edit-save-button')
                ->logout();
        });
    }

    /* @test */
    public function test_I_can_delete_a_option_group_successfully()
    {
        $user = User::where('name', 'Super Admin')->first();
        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/studies')
                ->assertSee('Studies')
                ->click('@study_title_dusk')
                ->pause(1000)
                ->click('@study_tools')
                ->pause(1000)
                ->click('@study_design')
                ->pause(1000)
                ->click('@options-group')
                ->assertSee('Option Groups')
                ->pause(1000)
                ->press('@optionGroup-navtab')
                ->press('@optionGroup-delete')
                ->assertDialogOpened('Are you sure to delete the option group?')
                ->pause(1000)
                ->acceptDialog('press OK')
                ->logout();
        });
    }

}
