<?php
/**
 * Created by PhpStorm.
 * User: mei
 * Date: 18-5-10
 * Time: 上午10:51
 */

namespace Kubill\LaraPush;


use Kubill\LaraPush\Contracts\Store;
use InvalidArgumentException;
use Kubill\LaraPush\Store\JPushStore;

class PusherManager
{
    /**
     * 应用实例
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * 推送实例存储数组
     *
     * @var array
     */
    protected $stores = [];

    /**
     * The registered custom driver creators.
     *
     * @var array
     */
    protected $customCreators = [];

    /**
     * 创建推送管理器实例
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * 从本地的stores变量中获取推送仓库
     *
     * @param  string $name
     * @return \Kubill\LaraPush\Contracts\Repository
     */
    protected function get($name)
    {
        return $this->stores[$name] ?? $this->resolve($name);
    }

    /**
     * 根据名称创建缓存驱动
     *
     * @param  string $name
     * @return \Kubill\LaraPush\Contracts\Repository
     *
     * @throws \InvalidArgumentException
     */
    protected function resolve($name)
    {
        $config = $this->getConfig($name);

        if (is_null($config)) {
            throw new InvalidArgumentException("Pusher store [{$name}] is not defined.");
        }

        if (isset($this->customCreators[$config['driver']])) {
            return $this->callCustomCreator($config);
        } else {
            $driverMethod = 'create' . ucfirst($config['driver']) . 'Driver';

            if (method_exists($this, $driverMethod)) {
                return $this->{$driverMethod}($config);
            } else {
                throw new InvalidArgumentException("Driver [{$config['driver']}] is not supported.");
            }
        }
    }

    /**
     * 根据名字获取推送存储实例
     *
     * @param  string|null $name
     * @return \Kubill\LaraPush\Contracts\Repository
     */
    public function store($name = null)
    {
        $name = $name ?: $this->getDefaultDriver();

        return $this->stores[$name] = $this->get($name);
    }

    /**
     * 通过给定的推送存储实现创建一个推送库
     *
     * @param  \Kubill\LaraPush\Contracts\Store $store
     * @return \Kubill\LaraPush\Repository
     */
    public function repository(Store $store)
    {
        $repository = new Repository($store);
        return $repository;
    }

    /**
     * 获取推送驱动的配置
     *
     * @param  string $name
     * @return array
     */
    protected function getConfig($name)
    {
        return $this->app['config']["push.stores.{$name}"];
    }

    /**
     * Call a custom driver creator.
     *
     * @param  array $config
     * @return mixed
     */
    protected function callCustomCreator(array $config)
    {
        return $this->customCreators[$config['driver']]($this->app, $config);
    }

    /**
     * 创建极光驱动实例
     *
     * @return \Kubill\LaraPush\Store\JPushStore
     */
    protected function createJpushDriver()
    {
        return $this->repository(new JPushStore);
    }

    /**
     * 获取默认的推送驱动名称
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->app['config']['push.default'];
    }

    /**
     * 动态调用默认驱动程序实例。
     *
     * @param  string $method
     * @param  array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->store()->$method(...$parameters);
    }
}
