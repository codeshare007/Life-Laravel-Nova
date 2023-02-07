<?php echo "<?php\n"; ?>

namespace <?php echo $vendor; ?>\<?php echo $package; ?>;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;

class ServiceProvider extends LaravelServiceProvider
{
    const CONFIG_PATH = __DIR__ . '/../config/<?php echo $configFileName; ?>.php';

    public function boot()
    {
        $this->publishes([
            self::CONFIG_PATH => config_path('<?php echo $configFileName; ?>.php'),
        ], 'config');

        Nova::serving(function (ServingNova $event) {
            Nova::script('<?php echo $configFileName; ?>', __DIR__.'/../dist/js/<?php echo $configFileName; ?>.js');
            Nova::style('<?php echo $configFileName; ?>', __DIR__.'/../dist/css/<?php echo $configFileName; ?>.css');
        });
    }

    public function register()
    {
        $this->mergeConfigFrom(
            self::CONFIG_PATH,
            '<?php echo $configFileName; ?>'
        );

        $this->app->bind('<?php echo $aliasName; ?>', function () {
            return new <?php echo $package; ?>();
        });

        $this->registerRoutes();
    }

    private function registerRoutes()
    {
        Route::domain(config('nova.domain', null))
            ->middleware(config('nova.middleware', []))
            ->prefix('/nova-vendor/wqa/<?php echo $configFileName; ?>')
            ->group(__DIR__ . '/Http/Routes/api.php');
    }
}
