<?php

namespace Kubill\LaraPush\Contracts;

interface Store
{
    /**
     * 推送消息
     *
     * @param  string $msg
     * @return array|mixed
     */
    public function send($msg, $parameters);
}
