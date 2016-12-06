<?php namespace link1st\Easemob;

use Illuminate\Support\ServiceProvider;

class TestServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

    /**
     * 引导程序
     *
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        // 发布配置文件 + 可以发布迁移文件
        $this->publishes([
            __DIR__.'/config/easemob.php' => config_path('easemob.php'),
        ]);

    }

	/**
     * 默认包位置
     *
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        // 将给定配置文件合现配置文件接合
        $this->mergeConfigFrom(
            __DIR__.'/config/easemob.php', 'easemob'
        );

        // 容器绑定
        $this->app->bind('Easemob', function () {
            return new easemob();
        });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [];
	}

}
