<?php namespace App\Bootstrap;

use App\Core\Log\AsWriter;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Bootstrap\ConfigureLogging as BaseConfigureLogging;
use Monolog\Handler\LogEntriesHandler;
use Monolog\Logger as Monolog;


class ConfigureLogging extends BaseConfigureLogging
{

    /**
     * Register the logger instance in the container.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return \Illuminate\Log\Writer
     */
    protected function registerLogger(Application $app)
    {
        $app->instance('log', $log = new AsWriter(
            new Monolog($app->environment()), $app['events'])
        );

        return $log;
    }



    /**
     * Custom Monolog handler .
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @param \Illuminate\Log\Writer $log
     * @return void
     */
    public function configureCustomHandler(Application $app, AsWriter $log)
    {
        if (getenv('APP_ENV') !== 'local') {
            $handler = new LogEntriesHandler(getenv('LOGENTRY_TOKEN'));
            $log->getMonolog()->pushHandler($handler);
        }

        $log->useDailyFiles($app->storagePath() . '/logs/laravel.log', 5);
    }
}
