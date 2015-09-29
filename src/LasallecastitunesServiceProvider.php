<?php

namespace Lasallecast\Lasallecastitunes;

/**
 *
 * iTunes feed package for the LaSalleCast e-broadcasting platform
 * Copyright (C) 2015  The South LaSalle Trading Corporation
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *
 * @package    iTunes feed package for the LaSalleCast e-broadcasting platform
 * @link       http://LaSalleCast.com
 * @copyright  (c) 2015, The South LaSalle Trading Corporation
 * @license    http://www.gnu.org/licenses/gpl-3.0.html
 * @author     The South LaSalle Trading Corporation
 * @email      info@southlasalle.com
 *
 */

// Laravel classes
use Illuminate\Support\ServiceProvider;


/**
 * This is the List Management service provider class.
 *
 * @author Bob Bloom <info@southlasalle.com>
 */
class LasallecastitunesServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;


    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->setupConfiguration();

        $this->setupViews();
    }


    /**
     * Setup the Configuration.
     *
     * @return void
     */
    public function setupConfiguration()
    {
        $configuration = realpath(__DIR__.'/../config/lasallecastitunes.php');

        $this->publishes([
            $configuration => config_path('lasallecastitunes.php'),
        ]);
    }


    /**
     * Register the application bindings.
     *
     * @return void
     */
    public function register()
    {
        $this->app['lasallecastitunes'] = $this->app->share(function($app)
        {
            return new Lasallecastitunes();
        });

    }


    /**
     * Define the views for the application.
     *
     * @return void
     */
    public function setupViews()
    {
        $this->loadViewsFrom(__DIR__.'/../views', 'lasallecastitunes');
    }


    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['lasallecastlistitunes'];
    }
}