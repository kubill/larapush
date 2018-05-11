<?php
/**
 * Created by PhpStorm.
 * User: mei
 * Date: 18-5-10
 * Time: 上午11:19
 */

namespace Kubill\LaraPush\Store;


use Kubill\LaraPush\Contracts\Store;
use JPush\Client;
use JPush\Exceptions\APIConnectionException;
use JPush\Exceptions\APIRequestException;

class JPushStore implements Store
{

    private $client;

    public function __construct()
    {
        $this->client = new Client(
            config('push.stores.jpush.app_key'),
            config('push.stores.jpush.secret'),
            storage_path('logs/push.log'));
    }

    /**
     * 推送消息
     *
     * @param  string $msg
     * @return array|mixed
     */
    public function send($msg, $parameters): array
    {
        $push = $this->client->push();
        $push->addAllAudience()
            ->setPlatform('all')
            ->setNotificationAlert($msg)
            ->iosNotification($msg, [
                'sound' => 'sound',
                'badge' => '+1',
                'extras' => $parameters
            ])
            ->androidNotification($msg, [
                'extras' => $parameters
            ]);
        return $this->push($push);
    }

    /**
     * @param $msg
     * @param $parameters
     * @return array|mixed
     */
    public function sendIos($msg, $parameters): array
    {
        return $this->push($this->buildIosData($msg, $parameters));
    }

    /**
     * @param $msg
     * @param $parameters
     * @return array|mixed
     */
    public function sendAndroid($msg, $parameters): array
    {
        return $this->push($this->buildAndroidData($msg, $parameters));
    }

    /**
     *构建IOS通知数据
     *
     * @param  string $msg
     * @param  array $parameters
     * @return \JPush\PushPayload
     */
    private function buildIosData($msg, $parameters)
    {
        $push = $this->client->push();
        $push->addAllAudience()
            ->iosNotification($msg, [
                'sound' => 'sound',
                'badge' => '+1',
                'extras' => [
                    $parameters
                ]
            ]);
        return $push;
    }

    /**
     * 构建android通知数据
     *
     * @param $msg
     * @param $parameters
     * @return \JPush\PushPayload
     */
    private function buildAndroidData($msg, $parameters)
    {
        $push = $this->client->push();
        $push->addAllAudience()
            ->androidNotification($msg, [
                'extras' => $parameters
            ]);
        return $push;
    }

    /**
     * 推送
     *
     * @param  \JPush\PushPayload $push
     * @return mixed
     */
    private function push($push)
    {
        try {
            $result = $push->send();
            return $result;
        } catch (APIConnectionException $e) {
            Log::error('jpushError', array('APIConnectionException' => $e->getMessage()));
        } catch (APIRequestException $e) {
            Log::error('jpushError', array('APIRequestException' => $e->getMessage()));
        }
    }
}
