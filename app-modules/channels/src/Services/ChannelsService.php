<?php
declare(strict_types=1);

namespace Modules\Channels\Services;

use App\Models\User;
use Modules\Channels\DTO\Channels\ChannelDTO;
use Modules\Channels\Models\Channel;
use Modules\Channels\Models\TelegramKey;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;

class ChannelsService
{
    /**
     * @param ChannelDTO $channelDTO
     * @return TelegramKey
     * @throws TelegramSDKException
     */
    public function saveChannel(ChannelDTO $channelDTO): TelegramKey
    {
        $telegramApi = new Api($channelDTO->token);
        $updateInfo = $telegramApi->getUpdates();

        $telegramChannelName = "";
        $telegramChannelPhotoId = "";

        foreach ($updateInfo as $update) {
            if ($update?->my_chat_member?->chat?->id === (int) $channelDTO->channel_id && $update?->my_chat_member?->chat?->type === 'channel' && empty($telegramChannelName)) {
                $telegramChannelName = $update?->my_chat_member?->chat?->title;
            }

            if (isset($update?->channel_post)) {
                if (isset($update?->channel_post?->new_chat_photo) && empty($telegramChannelPhotoId)) {
                    $telegramChannelPhotoId = $update?->channel_post?->new_chat_photo->filter(fn($photo) => $photo->width == 160 && $photo->height == 160)->first()->file_id;
                }
            }
        }

        if (!empty($telegramChannelPhotoId)) {
            $telegramChannelPhotoPath = $telegramApi->getFile([
                'file_id' => $telegramChannelPhotoId
            ]);

            $telegramPhoto = file_get_contents("https://api.telegram.org/file/bot{$channelDTO->token}/{$telegramChannelPhotoPath->file_path}");
            $path = "channels/avatars/{$telegramChannelPhotoId}";
            \Storage::put("public/$path", $telegramPhoto);
        }

        $telegramKey = TelegramKey::create($channelDTO->toArray());

        if (!empty($telegramChannelName)) {
            Channel::createOrUpdate([
                'name' => $telegramChannelName,
                'image' => $path ?? null,
                'telegram_key_id' => $telegramKey->id
            ]);
        }

        return $telegramKey;
    }

    /**
     * @param int $id
     * @param User $user
     * @return bool
     */
    public function deleteChannel(int $id, User $user): bool
    {
        return (bool) $user->telegramKeys()->whereId($id)->delete();
    }
}
