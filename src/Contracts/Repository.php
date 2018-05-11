<?php
/**
 * Created by PhpStorm.
 * User: mei
 * Date: 18-5-10
 * Time: 上午11:43
 */

namespace Kubill\LaraPush\Contracts;


interface Repository
{
    /**
     * 推送消息
     *
     * @param  string $msg
     * @return array|mixed
     */
    public function send($msg, $parameters);
}