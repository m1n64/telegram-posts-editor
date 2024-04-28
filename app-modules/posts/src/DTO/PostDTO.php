<?php
declare(strict_types=1);

namespace Modules\Posts\DTO;

use App\DTO\DTOToArrayInterface;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;

class PostDTO implements DTOToArrayInterface
{
    /**
     * @param string $title
     * @param string $content
     * @param int $telegram_key_id
     * @param int|null $post_id
     * @param Carbon|null $publish_date
     * @param array<UploadedFile> $photos
     */
    public function __construct(
        public string $title,
        public string $content,
        public int $telegram_key_id,
        public int|null $post_id = null,
        public Carbon|null $publish_date = null,
        public array $photos = [],
    )
    {
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            /*'id' => $this->id,*/
            'title' => $this->title,
            'content' => base64_encode($this->content),
            'telegram_key_id' => $this->telegram_key_id,
            'publish_date' => $this->publish_date,
        ];
    }
}
