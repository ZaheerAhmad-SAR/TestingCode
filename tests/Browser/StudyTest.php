<?php

namespace Tests\Browser;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class StudyTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_I_can_create_a_study() {
        $user = User::where('name', 'Super Admin')->first();
        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/studies')
                ->assertSee('Studies')
                ->press('@createStudy')
                ->waitForText('Add Study')
                ->pause(1000)
                ->type('@study_title', 'FLU in Pakistan ')
                ->type('@study_short_name', 'FLU 19')
                ->type('@study_code', '3130002')
                ->type('@protocol_number', '6436363')
                ->type('@trial_registry_id', '5884')
                ->type('@study_sponsor', 'CA')
                ->type('@start_date', '04-16-2021')
                ->type('@end_date', '05-16-2021')
                ->type('@description', 'This is a description of Study')
                ->press('@nav-Disease')
                ->waitFor('#nav-Disease')
                ->assertVisible('#nav-Disease')
                ->click('@add_field')
                ->waitFor('.add_field')
                ->assertVisible('.add_field')
                ->type('@disease_cohort_name','Week 1')
                ->press('@nav-users')
                ->waitFor('#nav-users')
                ->assertVisible('#nav-users')
                ->select('#select_users')
                ->click('#select_users_rightSelected')
                ->pause(5000)
                ->press('@create-study-button')
                ->logout();
        });
    }
    /* @test */
    public function test_I_can_change_a_study_status() {
        $user = User::where('name', 'Super Admin')->first();
        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/studies')
                ->assertSee('Studies')
                ->press('@study-navbar')
                ->press('@change_status')
                ->waitForText('Change Status')
                ->select('status', 'Development')
                ->press('@changeStudyStatusButton')
                ->logout();
        });
    }

    /* @test */
    public function test_I_can_update_a_study() {
        $user = User::where('name', 'Super Admin')->first();
        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/studies')
                ->assertSee('Studies')
                ->press('@study-navbar')
                ->press('@edit-study')
                ->waitForText('Edit study')
                ->type('@study_title', 'Original An Open Label, Multi-centered, Randomized Phase 2 Study to Evaluate the Safety, Tolerability and Bioactivity of Subcutaneous ACTH GeL in PAtients with Scleritis ')
                ->press('@create-study-button')
                ->logout();
        });
    }


    /* @test */
    public function test_I_can_clone_a_study() {
        $user = User::where('name', 'Super Admin')->first();
        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/studies')
                ->assertSee('Studies')
                ->press('@study-navbar')
                ->press('@clone-study-modal')
                ->waitForText('Clone Study')
                ->type('@study_title', 'Clone An Open Label,with Scleritis ')
                ->press('@nav-Clone')
                ->waitFor('#nav-Clone')
                ->assertVisible('#nav-Clone')
                ->pause('3434343434')
                ->press('@clone-study-submit-button')
                ->logout();
        });
    }
}
