<?php

namespace App\Channels;

use App\Models\Notification;

/**
 * Class DatabaseChannel - 消息通知数据库存储
 *
 * @package App\Channels
 */
class DatabaseChannel
{
    /**
     * 消息通知发送频道（数据库存储方式）
     *
     * @param              $notifiable
     * @param Notification $notification
     *
     * @return mixed
     */
    public function send($notifiable, Notification $notification)
    {
        return $notifiable->routeNotificationFor('database')->create([
            'type' => $notifiable->notification_type,
            'user_type' => $notifiable->notification_user_type,
            'notifiable_id' => $notifiable->notification_resource_id,
            'notifiable_type' => $notifiable->notification_resource_type,
            'data' => $notification->toDatabase($notifiable),
        ]);
    }
}

