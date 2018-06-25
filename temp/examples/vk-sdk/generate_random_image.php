<?php
require __DIR__ .'/../../../vendor/autoload.php';

use bheller\ImagesGenerator\ImagesGeneratorProvider;

@mkdir(__DIR__.'/img', 0777, true);
$faker = Faker\Factory::create();
$faker->addProvider(new ImagesGeneratorProvider($faker));
$image = $faker->imageGenerator(__DIR__.'/img', $faker->numberBetween(600, 800), $faker->numberBetween(400, 600), 'jpg', true, $faker->word, $faker->hexColor, $faker->hexColor);
echo $image;
