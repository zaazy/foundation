<?php

declare(strict_types=1);

namespace Zaaz\Foundation;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class FoundationServiceProvider extends ServiceProvider
{
    const CONFIG_PATH = __DIR__.'/../config/config.php';

    public function boot()
    {
        $this->publishes([
            self::CONFIG_PATH => config_path('zaaz.foundation.php'),
        ], 'config');
        // Add strip_tags validation rule
        Validator::extend('strip_tags', function ($attribute, $value) {
            return strip_tags($value) === $value;
        }, trans('validation.invalid_strip_tags'));

        // Add time offset validation rule
        Validator::extend('timeoffset', function ($attribute, $value) {
            return array_key_exists($value, timeoffsets());
        }, trans('validation.invalid_timeoffset'));
    }

    public function register()
    {
        $this->bindBlueprintMacros();
        $this->mergeConfigFrom(
            self::CONFIG_PATH,
            'support'
        );

        $this->app->bind('zaaz.foundation', function () {
            return new Foundation();
        });
    }

    protected function bindBlueprintMacros(): void
    {
        Blueprint::macro('auditable', function ($precision = 0, $softDeletes = false) {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            $this->timestamp('created_at', $precision)->nullable();
            $this->integer('created_by_id')->unsigned()->after('created_at')->nullable();
            $this->string('created_by_type')->after('created_at')->nullable();
            $this->timestamp('updated_at', $precision)->nullable();
            $this->integer('updated_by_id')->unsigned()->after('updated_at')->nullable();
            $this->string('updated_by_type')->after('updated_at')->nullable();
            if ($softDeletes) {
                $this->timestamp('deleted_at', $precision)->nullable();
                $this->integer('deleted_by_id')->unsigned()->nullable();
                $this->string('deleted_by_type')->nullable();
            }
        });

        Blueprint::macro('dropAuditable', function ($softDeletes = false) {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            $this->dropForeign($this->createIndexName('foreign', ['updated_by_type']));
            $this->dropForeign($this->createIndexName('foreign', ['updated_by_id']));
            $this->dropForeign($this->createIndexName('foreign', ['updated_at']));
            $this->dropForeign($this->createIndexName('foreign', ['created_by_type']));
            $this->dropForeign($this->createIndexName('foreign', ['created_by_id']));
            $this->dropForeign($this->createIndexName('foreign', ['created_at']));
            $columns = ['updated_by_type', 'updated_by_id', 'updated_at', 'created_by_type', 'created_by_id', 'created_at'];
            if ($softDeletes) {
                $this->dropForeign($this->createIndexName('foreign', ['deleted_at']));
                $this->dropForeign($this->createIndexName('foreign', ['deleted_by_type']));
                $this->dropForeign($this->createIndexName('foreign', ['deleted_by_id']));
                $columns = array_merge($columns, ['deleted_at', 'deleted_by_id', 'deleted_by_type']);
            }
            $this->dropColumn($columns);
        });
    }
}
