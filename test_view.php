<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $c = new \App\Http\Controllers\UserIdpController();
    $view = $c->showEmployee(203);
    $idp = $view->getData()['Idp'];
    echo "ID: " . $idp->id . "\n";
    echo "Tahun: " . $idp->tahun . "\n";
    echo "Target: " . $idp->target . "\n";
    echo "Penilaian: " . json_encode($idp->penilaian) . "\n";
} catch (\Exception $e) {
    echo $e->getMessage();
}
