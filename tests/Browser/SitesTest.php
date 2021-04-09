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
    public function test_I_can_create_a_sites_successfully() {
        $user = User::where('name', 'Super Admin')->first();
        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/sites')
                ->assertSee('Sites Detail')
                ->press('@siteModal')
                ->waitForText('Add New Site')
                ->pause(1000)
                ->type('@site_code', '123456789')
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
                ->waitFor('#primaryInvestigator')
                ->assertVisible('#primaryInvestigator')
                ->type('@pi_first_name','Amjad')
                ->type('@pi_mid_name','khan')
                ->type('@pi_last_name','gul')
                ->type('@pi_phone','43042125272')
                ->type('@pi_email','amjadkh5@hotmail.com')
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
                ->type('@c_phone','0941222509173')
                ->type('@c_email','abidkhatt096@yahoo.com')
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
                ->type('@photographer_phone','032282828820')
                ->type('@photographer_email','usman_kharrn@qapak.com')
                ->press('@photographer_button_save')
                ->pause(1000)
                ->press('@devices')
                ->waitFor('#devices')
                ->assertVisible('#devices')
                ->select('device_name','Heidelberg Spectralis')
                ->type('@device_serial','DSLR Alpha versionAbcfge')
                ->type('@device_software_version','v10-liteDSrrttt')
                ->press('@device_button_save')
                ->waitFor('.deviceSiteTableAppend')
                ->assertVisible('.deviceSiteTableAppend')
                ->pause(1000)
                ->press('@others')
                ->waitFor('#others')
                ->assertVisible('#others')
                ->type('@others_first_name','Faryal')
                ->type('@others_mid_name','khan')
                ->type('@others_last_name','Afridi')
                ->type('@others_phone','03331234245678')
                ->type('@others_email','faryafridi949@yahoo.com')
                ->press('@others_button_save')
                ->waitFor('.otherstableAppend')
                ->assertVisible('.otherstableAppend')
                ->pause(1000)
                ->logout();
        });
    }

    /** @test */
    public function test_I_can_update_a_sites_successfully() {
        $user = User::where('name', 'Super Admin')->first();
        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/sites')
                ->assertSee('Sites Detail')
                ->press('@sites-navtab')
                ->press('@sites-edit')
                ->waitForText('Edit Site')
                ->type('@site_code', '84848292929')
                ->press('@createSite')
                ->pause(1000)
                ->logout();
        });
    }

    /** @test */
    public function test_I_can_delete_a_sites_successfully() {
        $user = User::where('name', 'Super Admin')->first();
        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/sites')
                ->assertSee('Sites Detail')
                ->press('@sites-navtab')
                ->press('@sites-delete')
                ->assertDialogOpened('Are you sure to delete?')
                ->pause(1000)
                ->acceptDialog('press OK')
                ->pause(1000)
                ->logout();
        });
    }
}
