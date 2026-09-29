<?php

if (! function_exists('fmt_stok')) {
    function fmt_stok(float|int|string $nilai): string
    {
        return rtrim(rtrim(number_format((float) $nilai, 2, ',', '.'), '0'), ',');
    }
}
