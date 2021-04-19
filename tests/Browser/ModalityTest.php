<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\User;

class ModalityTest extends DuskTestCase
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
    public function test_I_can_create_a_parent_modality_successfully()
    {
        $user = User::where('name', 'Super Admin')->first();
        // this test the user create functioanlity
        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/modalities')
                ->assertSee('Modalities')
                ->press('@add-parent')
                ->waitForText('Add Parent')
                ->pause(1000)
                ->type('@parent-modality-name', 'Optical Coherence Tomography Angiography')
                ->type('@parent-modality-abbreviation', 'OCTA')
                ->press('@save-parent-modality')
                ->assertSee('Modalities')
                ->logout();
        });
    }

    public function test_I_can_update_a_parent_modality_successfully()
    {
        $user = User::where('name', 'Super Admin')->first();
        // this test the user create functioanlity
        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/modalities')
                ->assertSee('Modalities')
                ->press('@parent-modality-navtab')
                ->press('@parent-modality-edit')
                ->waitForText('Edit a Parent')
                ->pause(1000)
                ->press('@update-parent-modality')
                ->assertSee('Modalities')
                ->logout();

        });
    }

    public function test_I_can_clone_a_child_modality_from_parent_modalitiy_successfully()
    {
        $user = User::where('name', 'Super Admin')->first();
        // this test the user create functioanlity
        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/modalities')
                ->assertSee('Modalities')
                ->press('@parent-modality-navtab')
                ->press('@parent-modality-clone')
                ->pause(1000)
                ->assertSee('Modalities')
                ->logout();

        });
    }
    /* @test */

    public function test_I_can_delete_a_parent_modality_successfully()
    {
        $user = User::where('name', 'Super Admin')->first();
        // this test the user create functioanlity
        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/modalities')
                ->assertSee('Modalities')
                ->press('@parent-modality-navtab')
                ->press('@parent-modality-delete')
                ->assertDialogOpened('Are You sure want to delete !')
                ->acceptDialog('press OK')
                ->pause('15000')
                ->logout();

        });
    }


     /* @test */
    public function test_I_can_create_a_child_modality_successfully()
    {
        $user = User::where('name', 'Super Admin')->first();
        // this test the user create functioanlity
        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/modalities')
                ->assertSee('Modalities')
                ->press('@add-child')
                ->waitForText('Add Child')
                ->pause(1000)
                ->type('@child-modality-name', 'Child Test')
                ->select('#parent_id')
                ->press('@save-child-modality')
                ->assertSee('Modalities')
                ->logout();
        });
    }

    public function test_I_can_update_a_child_modality_successfully()
    {
        $user = User::where('name', 'Super Admin')->first();
        // this test the user create functioanlity
        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/modalities')
                ->assertSee('Modalities')
                ->click('.showPhasesSteps')
                ->pause(1000)
                ->press('@child-modality-navtab')
                ->press('@child-modality-edit')
                ->waitForText('Edit Child')
                ->pause(1000)
                ->press('@update-child-modality')
                ->assertSee('Modalities')
                ->logout();

        });
    }

    /* @test */

    public function test_I_can_delete_a_child_modality_successfully()
    {
        $user = User::where('name', 'Super Admin')->first();
        // this test the user create functioanlity
        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/modalities')
                ->assertSee('Modalities')
                ->click('.showPhasesSteps')
                ->pause(1000)
                ->press('@child-modality-navtab')
                ->press('@child-modality-delete')
                ->assertDialogOpened('Are You sure want to delete !')
                ->acceptDialog('press OK')
                ->pause('15000')
                ->logout();
        });
    }
}
