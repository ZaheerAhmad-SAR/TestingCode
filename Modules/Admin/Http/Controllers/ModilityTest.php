<?php

namespace Tests\Feature;


use App\User;
use Illuminate\Support\Str;
use Modules\Admin\Entities\Modility;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Foundation\Testing\WithFaker;



class ModilityTest extends TestCase
{
    use RefreshDatabase;

    use WithFaker;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTestData()
    {
        dd('heere in unit testBasicTestData');
        $response = $this->get('/');

        $response->assertStatus(200);
    }


    /** @test  */
    public function test_only_logged_in_users_can_see_modalities()
    {

        $response = $this->get('/modalities')->assertRedirect('/login');

    }

//    /** @test */
//
  public  function test_authenicated_users_can_see_the_modalities()
   {
        $this->actingAs(factory(Modility::class)->create());
        $response = $this->get('/modalities')
            ->assertOk();
//
        $this->withExceptionHandling();
//
        $id = (string)Str::uuid();
        $response = $this->post('/modalities',[

            'id' => $this->faker->$id,
           'modility_name'=> $this->faker->Pakistan
        ]);
        $response->assertOk();
        $this->assertCount(1,Modility::all());
    }

    /** @test */

    public  function test_authenicated_users_can_be_added_form_modalities()
    {
        $user = factory(User::class)->create();

        $this->withExceptionHandling();
//        $this->actingAs(factory(Modility::class)->create());
        $id = (string)Str::uuid();


        $response = $this->post('/modalities',[
            'id'=> $id,
            'modility_name'=> 'Parent Modility',
            'parent_yes'=>'yes'
        ]);

        $response->assertOk();
        $this->assertCount(0,Modility::all());

    }


}
