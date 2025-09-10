<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\POSAdapterServiceProvider::class,
    App\Providers\TenancyServiceProvider::class,
    POS\SwiftPos\App\Providers\SwiftPosServiceProvider::class,
    POS\AbsPos\App\Providers\AbsPosServiceProvider::class,
    POS\ShopfrontPos\App\Providers\ShopfrontPosServiceProvider::class,
];
