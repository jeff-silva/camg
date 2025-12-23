<?php

namespace App\Modules\Helpers;

use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    if (!class_exists('LogHelper')) {
      class_alias(LogHelper::class, 'LogHelper');
    }
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    // 
  }
}
