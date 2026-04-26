<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::first();
if ($user) {
    App\Models\User::where('id', $user->id)->update(['is_admin' => true]);
    auth()->login($user);
}

$httpKernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::create('/admin/products', 'GET');
if ($user) $request->setUserResolver(fn() => $user);

$response = $httpKernel->handle($request);
echo "Status: " . $response->getStatusCode() . "\n";
echo "Content: \n" . substr($response->getContent(), 0, 500);
