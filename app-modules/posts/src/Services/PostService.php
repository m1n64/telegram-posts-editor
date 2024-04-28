<?php
declare(strict_types=1);

namespace Modules\Posts\Services;

use App\Helpers\UrlHelper;
use Modules\Posts\DTO\PostDTO;
use Modules\Posts\Models\Post;

class PostService
{
    /**
     * @param UrlHelper $urlHelper
     * @param AttachmentService $attachmentService
     */
    public function __construct(
        protected UrlHelper $urlHelper,
        protected AttachmentService $attachmentService,
    )
    {
    }

    /**
     * @param PostDTO $postDTO
     * @return Post
     */
    public function save(PostDTO $postDTO): Post
    {
        $post = Post::updateOrCreate([
            'id' => $postDTO->post_id,
        ], $postDTO->toArray());

        $this->attachmentService->updateAttachments($post->id, $postDTO->photos);
        $post->load(['telegramKey', 'attachments']);

        return $post;
    }
}
