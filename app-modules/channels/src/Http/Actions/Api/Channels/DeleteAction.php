<?php
declare(strict_types=1);

namespace Modules\Channels\Http\Actions\Api\Channels;

use App\Http\Enums\HttpStatusesEnum;
use App\Http\Responses\ErrorJsonResponse;
use App\Http\Responses\SuccessJsonResponse;
use App\Models\User;
use Illuminate\Contracts\Support\Responsable;
use Modules\Channels\Services\ChannelsService;

class DeleteAction
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
     * @param int $id
     * @param User $user
     * @return Responsable
     */
    public function __invoke(int $id, User $user): Responsable
    {
        $isSuccess = $this->channelsService->deleteChannel($id, $user);

        if (!$isSuccess) {
            return new ErrorJsonResponse(
                message: 'Failed to delete channel',
                status: HttpStatusesEnum::NOT_FOUND,
            );
        }

        return new SuccessJsonResponse(
            data: [
                'is_deleted' => $isSuccess,
                'id' => $id,
            ]
        );
    }
}
