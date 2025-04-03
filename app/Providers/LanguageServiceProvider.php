<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class LanguageServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $languages = config('app.available_locales'); // Add more languages as needed
        $translationFiles = ['auth', 'passwords', 'validation']; // Add more translation files as needed

        if (!empty($languages)) {
            $this->loadTranslationsFrom(module_path('Common', 'lang'), 'common');
            foreach ($languages as $langKey => $lang) {
                foreach ($translationFiles as $file) {
                    $translations = trans("common::$file");
                    foreach ($translations as $key => $value) {
                        app('translator')->addLines([$file . '.' . $key => $value], $langKey);
                    }
                }
            }
        }
    }
}
