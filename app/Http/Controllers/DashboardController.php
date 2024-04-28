<?php

namespace App\Http\Controllers;

use App\Http\Actions\Dashboard\ContentEditorAction;
use App\Http\Actions\Dashboard\IndexAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Channels\Models\TelegramKey;
use Modules\Posts\Http\Resources\Api\Posts\AllPostsResource;
use Modules\Posts\Models\Post;

class DashboardController extends Controller
{
    /**
     * @param Request $request
     * @param IndexAction $action
     * @return Response
     */
    public function index(Request $request, IndexAction $action)
    {
        $bots = $action($request);

        return Inertia::render('Dashboard', [
            'bots' => $bots
        ]);
    }

    /**
     * @return Response|RedirectResponse
     */
    public function contentEditor(Request $request, ContentEditorAction $action, int $id, int $postId = null)
    {
        return $action($request, $id, $postId);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function history(Request $request, int $id)
    {
        $posts = $request->user()->telegramKeys()->whereId($id)->first()->posts()->paginate(Post::MAX_PER_PAGE);

        return Inertia::render('Content/History', [
            'channelId' => $id,
            'posts' => AllPostsResource::collection($posts),
        ]);
    }
}
