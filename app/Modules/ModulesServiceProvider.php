<?php namespace App\Modules;
 
/**
* ServiceProvider
*
* The service provider for the modules. After being registered
* it will make sure that each of the modules are properly loaded
* i.e. with their routes, views etc.
*
* @author Kamran Ahmed <kamranahmed.se@gmail.com>
* @package App\Modules
*/
class ModulesServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Will make sure that the required modules have been fully loaded
     * @return void
     */
    public function boot()
    {
        // For each of the registered modules, include their routes and Views
        $modules = config("module.modules");

        foreach ($modules as $module) {

            // Load the routes for each of the modules
            if(file_exists(__DIR__.'/'.$module.'/Routes/web.php')) {
                $this->loadRoutesFrom(__DIR__.'/'.$module.'/Routes/web.php');
            }

            // Load the views
            if(is_dir(__DIR__.'/'.$module.'/Views')) {
                $this->loadViewsFrom(__DIR__.'/'.$module.'/Views', $module);
            }

            // Load the translations
            if(is_dir(__DIR__.'/'.$module.'/Lang')) {
                $this->loadTranslationsFrom(__DIR__.'/'.$module.'/Lang', $module);
            }
            
        }
    }

    public function register() {}

}