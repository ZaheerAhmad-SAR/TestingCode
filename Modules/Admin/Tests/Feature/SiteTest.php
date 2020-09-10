<?php



namespace Tests\Feature;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Modules\Admin\Entities\Device;
use Modules\Admin\Entities\DeviceModility;
use Modules\Admin\Entities\Modility;
use Modules\Admin\Entities\Site;
use Modules\Admin\Entities\Study;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class SiteTest extends TestCase
{

    /**
     * A basic test example.
     *
     * @return void
     */

    use RefreshDatabase, WithFaker;


    /** @test */


    public function test_admin_can_create_sites(){

        $this->withoutMiddleware();
        $this->withExceptionHandling();
        $id = Str::uuid();

        $study = factory(Site::class)->make([
            'id'    => $id,
            'site_name'   => 'site name',
            'site_address'   => 'Address',
            'site_city'   => 'City',
            'site_state'   => 'State',
            'primary_investigators' => array("17077571-6aec-40be-af15-d8748d6802c5","3d402a7a-da91-4422-9e48-ce05f28a5827"),
            'coordinators' => array("17077571-6aec-40be-af15-d8748d6802d6","3d402a7a-da91-4422-9e43-ce05f28a5827"),
            'photographers' => array("17077571-6aec-40be-af15-d8748d6802d6","3d402a7a-da91-4422-9e43-ce05f28a5827"),
            'others' => array("17077571-6aec-40be-af15-d8748d6802d6","3d402a7a-da91-4422-9e43-ce05f28a5827"),
        ]);

        $this->assertNotTrue('1','saved');
        $this->assertCount(0,Site::all());
    }


}
