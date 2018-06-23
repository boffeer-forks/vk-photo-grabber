<?php
require __DIR__ .'/../../../vendor/autoload.php';

use bheller\ImagesGenerator\ImagesGeneratorProvider;

/**
 * @param string $dir
 * @param int $n
 * @return Generator
 */
function generateRandomImages(string $dir, int $n)
{
    @mkdir(__DIR__.'/img', 0777, true);
    $faker = Faker\Factory::create();
    $faker->addProvider(new ImagesGeneratorProvider($faker));

    foreach (range(1, $n) as $_) {
        $image = $faker->imageGenerator($dir, $faker->numberBetween(600, 800), $faker->numberBetween(400, 600), 'jpg', true, $faker->word, $faker->hexColor, $faker->hexColor);
        yield $image;
    }
}

/**
 * @param string $fileName
 * @param string $uploadUrl
 * @return array
 */
function vkUploadImage(string $fileName, string $uploadUrl)
{
    $post = ['file1' => new \CURLFile($fileName)];
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $uploadUrl);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $taskData = curl_exec($curl);

    if ($taskData === false) {
        throw new \RuntimeException(sprintf('Error (%d): %s', curl_errno($curl), curl_error($curl)));
    }

    return json_decode($taskData, true);
}

