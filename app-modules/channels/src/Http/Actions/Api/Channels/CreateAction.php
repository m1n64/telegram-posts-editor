<?php
declare(strict_types=1);

namespace Modules\Channels\Http\Actions\Api\Channels;

use App\Http\Enums\HttpStatusesEnum;
use App\Http\Responses\ErrorJsonResponse;
use App\Http\Responses\SuccessJsonResponse;
use Illuminate\Contracts\Support\Responsable;
use Modules\Channels\DTO\Channels\ChannelDTO;
use Modules\Channels\Services\ChannelsService;
use Telegram\Bot\Exceptions\TelegramSDKException;

class CreateAction
{
    /**
     * @param ChannelsService $channelsService
     */
    public function __construct(
        protected ChannelsService $channelsService,
    )
    {
    }

    /**
     * @param ChannelDTO $channelDTO
     * @return Responsable
     */
    public function __invoke(ChannelDTO $channelDTO): Responsable
    {
        try {
            $data = $this->channelsService->saveChannel($channelDTO)
                ->load(['channel']);

            return new SuccessJsonResponse(
                data: $data,
            );
        } catch (TelegramSDKException $exception) {
            return new ErrorJsonResponse(
                message: "Telegram Bot error",
                status: HttpStatusesEnum::INTERNAL_SERVER_ERROR,
            );
        }
    }
}
