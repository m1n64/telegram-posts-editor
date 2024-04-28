<?php
declare(strict_types=1);

namespace Modules\Channels\DTO\Channels;

use App\DTO\DTOToArrayInterface;

class ChannelDTO implements DTOToArrayInterface
{
    /**
     * @param string $name
     * @param string $token
     * @param string $channel_id
     * @param int $user_id
     */
    public function __construct(
        public string $name,
        public string $token,
        public string $channel_id,
        public int $user_id,
    )
    {
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'api_key' => $this->token,
            'channel_id' => $this->channel_id,
            'user_id' => $this->user_id,
        ];
    }
}
