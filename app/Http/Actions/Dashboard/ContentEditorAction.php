<?php
declare(strict_types=1);

namespace App\Http\Actions\Dashboard;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ContentEditorAction
{
    /**
     * @param Request $request
     * @param int $id
     * @param int|null $postId
     * @return Response|RedirectResponse
     */
    public function __invoke(Request $request, int $id, int $postId = null): Response|RedirectResponse
    {
        if ($postId) {
            $post = $request->user()->telegramKeys()->whereId($id)->first()->posts()->with(['attachments'])->whereId($postId)->first();
        }

        return Inertia::render('Content/Editor', [
            'channelId' => $id,
            'postId' => $postId,
            'post' => $post ?? null
        ]);
    }
}
