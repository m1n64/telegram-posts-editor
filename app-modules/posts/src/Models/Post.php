<?php

namespace Modules\Posts\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Channels\Models\TelegramKey;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    const MAX_PER_PAGE = 10;

    /**
     * @var string[]
     */
    protected $fillable = [
        'title',
        'content',
        'telegram_key_id',
        'publish_date',
        'status',
    ];

    /**
     * @var string[]
     */
    protected $appends = ['content_decoded'];

    /**
     * @return BelongsTo
     */
    public function telegramKey(): BelongsTo
    {
        return $this->belongsTo(TelegramKey::class);
    }

    /**
     * @return HasMany
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }

    /**
     * @return Attribute
     */
    protected function contentDecoded(): Attribute
    {
        return new Attribute(
            get: fn() => base64_decode($this->content)
        );
    }
}
