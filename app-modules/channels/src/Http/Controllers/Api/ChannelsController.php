<?php

namespace Modules\Channels\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\SuccessJsonResponse;
use Illuminate\Contracts\Support\Responsable;
use Modules\Channels\DTO\Channels\ChannelDTO;
use Modules\Channels\Http\Actions\Api\Channels\CreateAction;
use Modules\Channels\Http\Actions\Api\Channels\DeleteAction;
use Modules\Channels\Http\Requests\Api\Channels\CreateRequest;
use Modules\Channels\Http\Requests\Api\Channels\DeleteRequest;

class ChannelsController extends Controller
{
    /**
     * @param CreateRequest $request
     * @param CreateAction $action
     * @return Responsable
     */
    public function create(CreateRequest $request, CreateAction $action)
    {
        return $action(new ChannelDTO(...$request->validated() + ['user_id' => $request->user()->id]));;
    }

    /**
     * @param DeleteRequest $request
     * @param int $id
     * @param DeleteAction $action
     * @return Responsable
     */
    public function delete(DeleteRequest $request, int $id, DeleteAction $action)
    {
        return $action($id, $request->user());
    }
}
