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


class ModilityTest extends TestCase
{

    /**
     * A basic test example.
     *
     * @return void
     */


    use RefreshDatabase, WithFaker;


    public  function test_authenicated_users_can_be_added_form_modalities()
    {

        $this->withoutMiddleware();
        $this->withExceptionHandling();

        $id = (string)Str::uuid();

        $response = $this->post('/modalities', [
            'id' => $id,
            'modility_name' => 'Parent Modility',
        ]);


        $content = $response->getContent();
        $this->assertJson($content, 'data saved');

        $this->assertCount(0, Modility::all());
    }



    public function test_user_can_view_a_login_form()

    {
        $response = $this->get('/login');

        $response->assertSuccessful();
        $response->assertViewIs('auth.login');
    }

    public function test_user_cannot_view_a_login_form_when_authenticated()
    {
        $user = factory(User::class)->make();

        $response = $this->actingAs($user)->get('/login');

        $response->assertRedirect('/home');
    }

    public function test_user_can_login_with_correct_credentials()
    {
        $user_id = (string)Str::uuid();
        $user = factory(User::class)->create([
            'id'    => '808858de-c729-45d9-8552-dcec0642e08d',
            'password' => bcrypt($password = 'i-love-laravel'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertRedirect('/home');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_incorrect_password()
    {
        $user = factory(User::class)->create([
            'id'    => '808858de-c729-45d9-8552-dcec0642e08d',
            'password' => bcrypt('i-love-laravel'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => 'invalid-password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    public function test_remember_me_functionality()
    {
        $user = factory(User::class)->create([
            'id'    => '808858de-c729-45d9-8552-dcec0642e08d',
            'password' => bcrypt($password = 'i-love-laravel'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => $password,
            'remember' => 'on',
        ]);

        $response->assertRedirect('/home');
        // cookie assertion goes here
        $this->assertAuthenticatedAs($user);
    }

    public function test_register_form_displayed()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }


    public function testBasicExampleModilityName()
    {
        $id = (string)Str::uuid();

        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->json('POST', '/modalities', ['id'=>$id,'modility_name' => 'Sally']);

        //dd($response,$id);

        $response
            ->assertStatus(200)
            ->assertJson([
                'created' => true,
            ]);
    }


    public function test_registers_a_valid_user()
    {
        $user = factory(User::class)->make();

        $response = $this->post('register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);
        $response->assertStatus(302);

        $this->assertAuthenticated();
    }

    public function test_admin_can_create_roles(){
        $role = 'admin';
        $response = $this->post('roles', [
            'name' => $role,
            'description'   => 'description of the role'
        ]);

        $response->assertStatus(302);
    }


    public function test_does_send_password_reset_email()
    {
        $user = factory(User::class)->create();




        $response = $this->get('/modalities')->assertRedirect('/login');

        $this->expectsNotification($user, ResetPassword::class);


        $response = $this->post('password/email', ['email' => $user->email]);

        $response->assertStatus(302);
    }





    /** @test */

    public  function test_authenicated_users_can_see_the_modalities()
    {

        //if (Auth::user()) {
            //dd('lljjjjjj');


            $this->be($modility = factory('Modules\Admin\Entities\Modility')->create());
            //$this->actingAs(factory(Modility::class)->create());
            $response = $this->get('/modalities')->assertOk();



        //}

//        $this->withExceptionHandling();
//
//        $id = (string)Str::uuid();
//        $response = $this->post('/modalities',[
//
//            'id' => $this->faker->$id,
//            'modility_name'=> $this->faker->Pakistan
//        ]);
//        $response->assertOk();
//        $this->assertCount(1,Modility::all());

    }

    public function test_does_not_send_password_reset_email()
    {
        $this->doesntExpectJobs(ResetPassword::class);


        $this->post('password/email', ['email' => 'invalid@email.com']);
    }

    public function test_changes_a_users_password()
    {

        $this->withoutMiddleware();
        $this->withExceptionHandling();
        $this->actingAs(factory(Modility::class)->create());
        $id = (string)Str::uuid();


        $response = $this->post('/modalities',[
            'id'=> $id,
            'modility_name'=> 'Parent Modility'
        ]);

        //$this->assertCount(0,Modility::all());
        $this->assertCount(1,Modility::all());
        $response->assertOk();

        $user = factory(User::class)->create();

        $token = Password::createToken($user);

        $response = $this->post('/password/reset', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }

    public function test_admin_can_create_devices(){
        $this->withoutMiddleware();
        $this->withExceptionHandling();
        $id = (string)Str::uuid();

        $modility = factory(Modility::class)->make([
            'id' => $id,
            'modility_name' => 'Example Modility',
            ]);
        $device = factory(Device::class)->make([
            'id'    => 'a18b9a51-d703-48e4-aff0-df2454fe56e9',
            'device_name'   => 'example device',
             'modalities' => array("17077571-6aec-40be-af15-d8748d6802c5","3d402a7a-da91-4422-9e48-ce05f28a5827")
        ]);


    }

    public function test_admin_can_create_studies(){

        $this->withoutMiddleware();
        $this->withExceptionHandling();
        $id = (string)Str::uuid();

        $study = factory(Study::class)->make([
            'id'    => $id,
            'study_short_name'   => 'study short name',
            'study_title'   => 'study_title study_title study_title study_title study_title study short name',
            'study_code'   => '98',
            'protocol_number'   => 'OIRRC-2020-09',
            'study_phase'   => 'Phase 1',
            'trial_registry_id'   => '09090',
            'users' => array("17077571-6aec-40be-af15-d8748d6802c5","3d402a7a-da91-4422-9e48-ce05f28a5827"),
            'sites' => array("17077571-6aec-40be-af15-d8748d6802d6","3d402a7a-da91-4422-9e43-ce05f28a5827"),
        ]);

        $this->assertNotTrue('1','saved');
        $this->assertCount(0,Study::all());
    }






}
