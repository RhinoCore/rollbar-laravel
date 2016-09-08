<?php 

namespace RhinoCore\Rollbar;

use Illuminate\Support\ServiceProvider;
use Rollbar;

class RollbarServiceProvider extends ServiceProvider
{
    
    public function register()
    {   
        
        if ($this->guardConfigurations()) {
            
            $app = $this->app;

            $defaults = [
                'environment'  => $app->environment(),
                'root'         => base_path(),
            ];

            $config = array_merge($defaults, $app['config']->get('services.rollbar', []));
            $config['access_token'] = env('ROLLBAR_TOKEN') ?: $app['config']->get('services.rollbar.access_token');

            if (empty($config['access_token'])) {
                throw new \InvalidArgumentException('Rollbar access token not configured');
            }
            
            \Rollbar::init($config);
        }
    }

    private function guardConfigurations()
    {
        return !empty(env('ROLLBAR_TOKEN')) && !empty($this->app['config']->get('services.rollbar'));       
    }
}