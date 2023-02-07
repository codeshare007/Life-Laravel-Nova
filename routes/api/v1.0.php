<?php

Route::any('{route}/{action?}', function() {
    return ['Message' => 'Legacy versions 1.0.x are not currently supported with this API'];
});
