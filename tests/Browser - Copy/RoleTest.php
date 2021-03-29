<?php

namespace Tests\Browser;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RoleTest extends DuskTestCase
{
//    public function setUp(): void
//    {
//        $this->appUrl = env('APP_URL');
//        parent::setUp();
//        //$this->artisan('migrate:refresh');
//        $this->artisan('module:seed');
//    }

    /**
     * A Dusk test example.
     *
     * @return void
     */
    /// Create a New Role
    ///
    public function test_i_can_create_a_new_role()
    {
        $user = User::where('name', 'Super Admin')->first();
        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/roles')
                ->assertSee('Roles Detail')
                ->click('@create-role')
                ->waitFor('#createRole')
                ->assertVisible('#createRole')
                ->assertSee('Add New Role')
                ->type('name','JS Developer')
                ->type('description','This role is for JS developer only!!!!')
                ->radio('role_type_name','study_role')
                ->click('@nav-StudyActivities')
                ->waitFor('#nav-StudyActivities')
                ->assertVisible('#nav-StudyActivities')
                ->check('dashboard_add')
                ->check('dashboard_edit')
                ->check('dashboard_view')
                ->check('adjudication_add')
                ->uncheck('adjudication_edit')
                ->check('adjudication_view')
                ->uncheck('adjudication_delete')
                ->check('eligibility_add')
                ->uncheck('eligibility_edit')
                ->check('eligibility_view')
                ->uncheck('eligibility_delete')
                ->uncheck('grading_add')
                ->uncheck('grading_edit')
                ->check('grading_view')
                ->uncheck('grading_delete')
                ->uncheck('qualityControl_add')
                ->uncheck('qualityControl_edit')
                ->check('qualityControl_view')
                ->uncheck('qualityControl_delete')
                ->check('queries_add')
                ->uncheck('queries_edit')
                ->check('queries_view')
                ->uncheck('queries_delete')
                ->check('study_add')
                ->uncheck('study_edit')
                ->check('study_view')
                ->uncheck('study_delete')
                ->check('subjects_add')
                ->uncheck('subjects_edit')
                ->check('subjects_view')
                ->uncheck('subjects_delete')
                ->check('bug_reporting_add')
                ->uncheck('bug_reporting_edit')
                ->check('bug_reporting_view')
                ->check('bug_reporting_delete')
                ->click('@nav-ManagementActivities')
                ->waitFor('#nav-ManagementActivities')
                ->assertVisible('#nav-ManagementActivities')
                ->check('system_tools')
                ->check('study_tools')
                ->uncheck('management')
                ->uncheck('activity_log')
                ->uncheck('certification')
                ->uncheck('finance')
                ->click('@nav-CertificationApp')
                ->waitFor('#nav-CertificationApp')
                ->assertVisible('#nav-CertificationApp')
                ->check('view_certificate')
                ->uncheck('generate_certificate')
                ->uncheck('certificate_preferences')
                ->click('@create-new-roles')
                ->assertSee('Roles Detail')
                ->visit('/roles');
        });
    }

    public function test_i_can_update_a_new_role()
    {
        $user = User::where('name', 'Super Admin')->first();
        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/roles')
                ->assertSee('Roles Detail')
                ->press('Edit')
//                ->click('@create-role')
//                ->waitFor('#createRole')
//                ->assertVisible('#createRole')
                ->assertSee('Update Role')
//                ->type('name','JS Developer')
//                ->type('description','This role is for JS developer only!!!!')
//                ->radio('role_type_name','study_role')
//                ->click('@nav-StudyActivities')
//                ->waitFor('#nav-StudyActivities')
//                ->assertVisible('#nav-StudyActivities')
//                ->check('dashboard_add')
//                ->check('dashboard_edit')
//                ->check('dashboard_view')
//                ->check('adjudication_add')
//                ->uncheck('adjudication_edit')
//                ->check('adjudication_view')
//                ->uncheck('adjudication_delete')
//                ->check('eligibility_add')
//                ->uncheck('eligibility_edit')
//                ->check('eligibility_view')
//                ->uncheck('eligibility_delete')
//                ->uncheck('grading_add')
//                ->uncheck('grading_edit')
//                ->check('grading_view')
//                ->uncheck('grading_delete')
//                ->uncheck('qualityControl_add')
//                ->uncheck('qualityControl_edit')
//                ->check('qualityControl_view')
//                ->uncheck('qualityControl_delete')
//                ->check('queries_add')
//                ->uncheck('queries_edit')
//                ->check('queries_view')
//                ->uncheck('queries_delete')
//                ->check('study_add')
//                ->uncheck('study_edit')
//                ->check('study_view')
//                ->uncheck('study_delete')
//                ->check('subjects_add')
//                ->uncheck('subjects_edit')
//                ->check('subjects_view')
//                ->uncheck('subjects_delete')
//                ->check('bug_reporting_add')
//                ->uncheck('bug_reporting_edit')
//                ->check('bug_reporting_view')
//                ->check('bug_reporting_delete')
//                ->click('@nav-ManagementActivities')
//                ->waitFor('#nav-ManagementActivities')
//                ->assertVisible('#nav-ManagementActivities')
//                ->check('system_tools')
//                ->check('study_tools')
//                ->uncheck('management')
//                ->uncheck('activity_log')
//                ->uncheck('certification')
//                ->uncheck('finance')
//                ->click('@nav-CertificationApp')
//                ->waitFor('#nav-CertificationApp')
//                ->assertVisible('#nav-CertificationApp')
//                ->check('view_certificate')
//                ->uncheck('generate_certificate')
//                ->uncheck('certificate_preferences')
//                ->click('@create-new-roles')
//                ->assertSee('Roles Detail')

                ->visit('/roles');
        });
    }


}
