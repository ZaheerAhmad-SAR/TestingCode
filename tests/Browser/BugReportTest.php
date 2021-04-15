<?php

namespace Tests\Browser;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class BugReportTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_user_can_report_a_new_bug() {
        $user = User::where('name', 'Super Admin')->first();
        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/studies')
                ->assertSee('Studies')
                ->press('@support-button')
                ->pause(1000)
                ->press('@reportabugmodel')
                ->waitFor('#reportabugmodel')
                ->assertVisible('#reportabugmodel')
                ->waitForText('Report a Bug')
                //->pause(7000)
                ->type('@shortTitle', 'How to Create a Study!')
                ->type('@yourMessage', 'Looking forward to hear more about it!')
                ->attach('attachFile', storage_path('dusk_testing/dusk_test_profile.png'))
                ->radio('severity','medium')
                ->click('@submit-button')
                ->logout();
        });
    }

    /** @test */

    public function test_user_can_change_the_status_of_bug() {
        $user = User::where('name', 'Super Admin')->first();
        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/bug-reports')
                ->assertSee('Bug Reports')
                ->press('@bug-navtab')
                ->press('@bug-edit')
                ->waitForText('Edit Report a Bug')
                ->pause(100)
                ->type('@developerComment', 'Click the gear button next to the action column!')
                ->radio('editSeverity','medium')
                ->radio('editStatus','open')
                ->select('openStatus','Available')
                ->click('@submit-button-save')
                ->logout();
        });
    }

}
