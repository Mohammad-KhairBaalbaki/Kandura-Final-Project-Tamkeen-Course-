<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Switch the application language
     */
    public function switch($locale)
    {
        // Validate locale
        if (!in_array($locale, ['en', 'ar'])) {
            abort(400, 'Invalid locale');
        }

        // Store locale in session
        Session::put('locale', $locale);

        // Redirect back to previous page
        return redirect()->back()->with('status', __('Language changed successfully'));
    }
}
