<?php

namespace Modules\Posts\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\SuccessJsonResponse;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Modules\Posts\DTO\PostDTO;
use Modules\Posts\DTO\Responses\SavedPostDTO;
use Modules\Posts\Http\Actions\Api\Posts\RemoveFromQueueAction;
use Modules\Posts\Http\Actions\Api\Posts\SaveAction;
use Modules\Posts\Http\Actions\Api\Posts\SendAction;
use Modules\Posts\Http\Requests\Api\Posts\SaveRequest;
use Modules\Posts\Http\Requests\Api\Posts\ScheduleRequest;

class PostsController extends Controller
{
    /**
     * @param SaveRequest $request
     * @param SaveAction $createAction
     * @return Responsable
     */
    public function save(SaveRequest $request, SaveAction $createAction)
    {
        $dto = new PostDTO(...$request->validated());

        return $this->saveAction($request, $dto, $createAction);
    }

    /**
     * @param SaveRequest $request
     * @param SendAction $sendAction
     * @return Responsable
     */
    public function send(SaveRequest $request, SendAction $sendAction)
    {
        return $this->save($request, $sendAction);
    }

    /**
     * @param ScheduleRequest $request
     * @param SendAction $sendAction
     * @return Responsable
     */
    public function schedule(ScheduleRequest $request, SendAction $sendAction)
    {
        $dto = new PostDTO(...array_merge($request->validated(), ['publish_date' => Carbon::createFromTimestamp($request->input('publish_date'))]));

        return $this->saveAction($request, $dto, $sendAction);
    }

    /**
     * @param Request $request
     * @param RemoveFromQueueAction $action
     * @param int $telegramKeyId
     * @param string $jobUuid
     * @return Responsable
     */
    public function removeFromQueue(Request $request, RemoveFromQueueAction $action, int $telegramKeyId, string $jobUuid)
    {
        return $action($jobUuid, $request->user()->id);
    }

    /**
     * @param Request $request
     * @param PostDTO $dto
     * @param SaveAction $action
     * @return Responsable
     */
    protected function saveAction(Request $request, PostDTO $dto, SaveAction $action): Responsable
    {
        $postId = $action($dto, $request->user()->id);

        return new SuccessJsonResponse(
            data: new SavedPostDTO($postId)
        );
    }
}
