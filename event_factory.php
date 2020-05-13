<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Event_model::class, function (Faker\Generator $faker) {

    $messages = [
        ['info', 'reportdata', 'new_client', []],
        ['success', 'munkireport', 'munki.package_installed', ['count' => 3]],
        ['warning', 'diskreport', 'free_disk_space_less_than', ['gb' => 10]],
        ['danger', 'munkireport', 'munki.error', ['count' => 10]],
    ];

    list($type, $module, $msg, $data) = $faker->randomElement($messages);

    return [
        'type' => $type,
        'module' => $module,
        'msg' => $msg,
        'data' => json_encode($data),
        'timestamp' =>$faker->dateTimeBetween('-1 month')->format('U'),
    ];
});