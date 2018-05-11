<?php

namespace Kubill\LaraPush;


use Kubill\LaraPush\Contracts\Store;
use \Kubill\LaraPush\Contracts\Repository as RepositoryInterface;

class Repository implements RepositoryInterface
{
    /**
     * The cache store implementation.
     *
     * @var \Illuminate\Contracts\Cache\Store
     */
    protected $store;

    /**
     * Create a new cache repository instance.
     *
     * @param  \Kubill\LaraPush\Contracts\Store;  $store
     * @return void
     */
    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    /**
     * 推送消息
     *
     * @param  string $msg
     * @return array|mixed
     */
    public function send($msg, $parameters)
    {
        return $this->store->send($msg, $parameters);
    }

}
