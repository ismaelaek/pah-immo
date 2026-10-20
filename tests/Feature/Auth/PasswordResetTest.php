<?php

declare(strict_types=1);

use Filament\Facades\Filament;

test('reset password link screen can be rendered', function () {
    $this->get(Filament::getRequestPasswordResetUrl())->assertOk();
});
