<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Features\SupportFileUploads\FileUploadConfiguration;

$manager = $app->make('filesystem');
$disk = $manager->disk('local');
echo get_class($disk) . PHP_EOL;
$adapter = $disk->getAdapter();
echo get_class($adapter) . PHP_EOL;
echo method_exists($adapter, 'getTemporaryUrl') ? 'adapter has getTemporaryUrl' : 'adapter NO getTemporaryUrl';
echo PHP_EOL;

Storage::disk('local')->put('livewire-tmp/test.png', 'hello');
$path = 'test.png';
config(['livewire.temporary_file_upload.disk' => 'local']);
$tempFile = new TemporaryUploadedFile($path, 'local');
$url = $tempFile->temporaryUrl();
echo $url . PHP_EOL;
