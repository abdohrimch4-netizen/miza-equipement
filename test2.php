<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = new App\Models\User(['name'=>'Test', 'email'=>'test@test.com', 'is_admin'=>true]);
auth()->login($user);

try {
    echo app(App\Http\Controllers\ProductController::class)->create()->render();
} catch (\Throwable $e) {
    echo $e->getMessage() . "\n" . $e->getFile() . ":" . $e->getLine();
}
