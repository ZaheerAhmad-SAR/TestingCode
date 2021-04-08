<?php

namespace Tests\Browser;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class SitesTest extends DuskTestCase
{

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_I_can_create_a_sites_successfully()
    {
        $user = User::where('name', 'Super Admin')->first();
        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/sites')
                ->assertSee('Sites Detail')
                ->press('@siteModal')
                ->waitForText('Add New Site')
                ->pause(1000)
                ->type('@site_code', '2015444')
                ->type('@site_name', 'ExampleAddd')
                ->type('@autocomplete', '313 West Maude Avenue')
                ->type('@fullAddr', '313 West Maude Avenue')
                ->type('@locality', 'Sunnyvale')
                ->type('@administrative_area_level_1', 'CA')
                ->type('@postal_code', '12453')
                ->type('@country', 'United States')
                ->type('@site_phone', '13052329272')
                ->press('@createSite')
                ->pause(1000)
                ->press('@primaryInvestigator')
                ->pause(1000)
                ->type('@pi_first_name','Amjad')
                ->type('@pi_mid_name','khan')
                ->type('@pi_last_name','gul')
                ->type('@pi_phone','43052325272')
                ->type('@pi_email','amjadkhan89@hotmail.com')
                ->press('@pi_button_save')
                ->pause(1000)
                ->waitFor('.primaryInvestigatorTableAppend')
                ->assertVisible('.primaryInvestigatorTableAppend')
                ->press('@coordinator')
                ->waitFor('#coordinator')
                ->assertVisible('#coordinator')
                ->type('@c_first_name','Abid')
                ->type('@c_mid_name','khattak')
                ->type('@c_last_name','khan')
                ->type('@c_phone','09392209191')
                ->type('@c_email','abidkhattak09@yahoo.com')
                ->press('@c_button_save')
                ->pause(1000)
                ->waitFor('.CtableAppend')
                ->assertVisible('.CtableAppend')
                ->pause(1000)
                ->press('@photographer')
                ->waitFor('#photographer')
                ->assertVisible('#photographer')
                ->type('@photographer_first_name','Usman')
                ->type('@photographer_mid_name','khan')
                ->type('@photographer_last_name','khalil')
                ->type('@photographer_phone','03828282882')
                ->type('@photographer_email','usman@qapak.com')
                ->press('@photographer_button_save')
                ->pause(1000)
                ->press('@devices')
                ->waitFor('#devices')
                ->assertVisible('#devices')
                ->select('device_name','Canon CX2')
                ->type('@device_serial','DSLR Alpha version')
                ->type('@device_software_version','v10-lite')
                ->press('@device_button_save')
                ->pause(10000)
                ->logout();
        });
    }
}
