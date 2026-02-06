<?php

namespace App\Services\Web;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class LanguageService
{
    public function switch($locale)
    {
        return DB::transaction(function () use ($locale) {
            if (! in_array($locale, ['en', 'ar'], true)) {
                abort(400, 'Invalid locale');
            }

            Session::put('locale', $locale);

            return $locale;
        });
    }
}
