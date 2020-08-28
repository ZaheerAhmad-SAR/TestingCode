<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

use Illuminate\Support\Str;


$factory->define(\Modules\Admin\Entities\Modility::class, function (Faker $faker) {
    $id =  Str ::uuid();
    return [

    'id'=>$faker->uuid,
     'modility_name'=>$faker->name
    ];
});

$factory->define(\Modules\Admin\Entities\DeviceModility::class, function (Faker $faker) {
    return [
        'id'=>$faker->uuid,
        'modility_id'=>$faker->uuid,
        'device_id'=>$faker->uuid
    ];
});

$factory->define(\Modules\Admin\Entities\Device::class, function (Faker $faker) {
    return [
        'id'=>$faker->uuid,
        'device_name'=>$faker->name,
    ];
});


$factory->define(\Modules\Admin\Entities\Study::class, function (Faker $faker) {
    return [
        'id'=>$faker->uuid,
        'study_short_name'=>$faker->name,
        'study_title'=>$faker->paragraph,
        'study_code'=>$faker->randomDigit,
        'protocol_number'=>$faker->randomDigit,
        'study_phase'=>$faker->name,
        'trial_registry_id'=>$faker->randomNumber(),
    ];
});

$factory->define(\Modules\Admin\Entities\Site::class,function (Faker $faker) {
    return[
   'id' => $faker->uuid,
   'site_name' => $faker->streetName,
   'site_address' => $faker->address,
   'site_city' => $faker->city,
   'site_state' => $faker->state,
        ];
});
