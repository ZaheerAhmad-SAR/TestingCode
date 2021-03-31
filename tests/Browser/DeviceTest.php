<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\User;
use Tests\Browser\Pages\Modality;

class DeviceTest extends DuskTestCase
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
    public function test_I_can_create_a_device_successfully()
    {
        $user = User::where('name', 'Super Admin')->first();
        // this test the user create functioanlity 
        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                    ->visit(new Modality)
                    ->fillInModalityForm('Color Fundus Photography', 'CFP');
                    ->visit('/devices')
                    ->assertSee('Devices Detail')
                    ->press('Add Device')
                    ->waitForText('New Device')
                    ->pause(1000)
                    ->type('#device_name','Codek Pak')
                    ->type('#device_model','Codek, Inc')
                    ->type('#device_manufacturer','Amir Khan')
                    ->click('#nav-profile-tab')
                    ->pause(1000)
                    ->select('#select_modalities')
                    ->click('#select_modalities_rightSelected')
                    ->pause(1000)
                    ->press('Save Changes')
                    ->assertSee('Devices Detail')
                    ->logout();
        });
        
    }

    public function test_I_can_update_a_device_successfully()
    {
        $user = User::where('name', 'Super Admin')->first();
        // this test the user create functioanlity 
        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                    ->visit(new Modality)
                    ->fillInModalityForm('Fundus Autofluorescence', 'FA');
                    ->visit('/devices')
                    ->assertSee('Devices Detail')
                    ->press('.fa-cog')
                    ->press('#edit-device')
                    ->waitForText('Edit Device')
                    ->pause(1000)
                    ->type('#device_name','Codek Pak-Test')
                    ->type('#device_model','Codek, Inc-Test')
                    ->type('#device_manufacturer','Amir Khan-Test')
                    ->click('#nav-profile-tab')
                    ->pause(1000)
                    ->click('#select_modalities_rightSelected')
                    ->pause(1000)
                    ->press('Save Changes')
                    ->assertSee('Devices Detail')
                    ->logout();
        });
        
    }
}
