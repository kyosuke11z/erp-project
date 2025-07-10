<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
    App\Providers\VoltServiceProvider::class, // Volt must be registered before the PDF provider
];
