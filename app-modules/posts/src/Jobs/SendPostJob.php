<?php

namespace Modules\Posts\Jobs;

use App\Helpers\UrlHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Auth\Enums\NotificationStatusesEnum;
use Modules\Auth\Events\NotificationEvent;
use Modules\Posts\Enums\StatusesEnum;
use Modules\Posts\Models\Post;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Objects\InputMedia\InputMediaPhoto;

class SendPostJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const PARSE_MODE = 'MarkdownV2';

    /**
     * @param Post $post
     */
    public function __construct(
        public Post $post,
    )
    {
    }

    /**
     * Execute the job.
     * @throws TelegramSDKException
     */
    public function handle(): void
    {
        $urlHelper = new UrlHelper();

        $telegramKey = $this->post->telegramKey()->with(['user'])->first();

        try {
            $tgApi = new Api($telegramKey->api_key);

            $text = $urlHelper->encodeURIComponent(addslashes($this->post->content_decoded));

            $attachments = $this->post->attachments();
            if ($attachments->count() > 0) {
                $files = [];

                foreach ($attachments->get() as $attachment) {
                    $files[] = (new InputMediaPhoto())
                        ->media(InputFile::file(\Storage::path("public/$attachment->path")));
                }

                $files[0]->caption($text)
                    ->parseMode(self::PARSE_MODE);

                $tgApi->sendMediaGroup([
                    'chat_id' => $telegramKey->channel_id,
                    'media' => $files,
                ]);
            } else {
                $tgApi->sendMessage([
                    'chat_id' => $telegramKey->channel_id,
                    'text' => $text,
                    'parse_mode' => self::PARSE_MODE,
                ]);
            }

            $this->post->update([
                'status' => StatusesEnum::PUBLISHED->value,
            ]);

            event(new NotificationEvent("Post was successfully sent", NotificationStatusesEnum::SUCCESS, $telegramKey->user->id));
        } catch (TelegramSDKException $e) {
            $this->post->update([
                'status' => StatusesEnum::ERROR->value,
            ]);

            event(new NotificationEvent("Error sending post: {$e->getMessage()}", NotificationStatusesEnum::ERROR, $telegramKey->user->id));

            throw $e;
        }
    }
}
