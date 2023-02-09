<?php

namespace App\Http\Traits;

trait WebpSupport
{
    public function supportWebp(): bool
    {
        return (
            (
                isset($_SERVER['HTTP_ACCEPT']) &&
                strpos($_SERVER['HTTP_ACCEPT'], 'image/webp')
            ) !== false
        );
    }
}
