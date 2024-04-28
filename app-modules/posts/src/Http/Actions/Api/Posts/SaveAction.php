<?php
declare(strict_types=1);

namespace Modules\Posts\Http\Actions\Api\Posts;

use Modules\Posts\DTO\PostDTO;
use Modules\Posts\Services\PostService;
use Telegram\Bot\Api;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Objects\InputContent\InputMessageContent;
use Telegram\Bot\Objects\InputMedia\InputMedia;
use Telegram\Bot\Objects\InputMedia\InputMediaPhoto;

class SaveAction
{
    /**
     * @param PostService $postService
     */
    public function __construct(
        public PostService $postService,
    )
    {
    }

    /**
     * @param PostDTO $dto
     * @return int
     */
    public function __invoke(PostDTO $dto): int
    {
        return $this->postService->save($dto)->id;
    }
}
