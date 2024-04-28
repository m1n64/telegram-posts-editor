<?php

namespace Modules\Channels\Models;

use App\Models\FullPathImageTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Storage;

class Channel extends Model
{
    use HasFactory, SoftDeletes, FullPathImageTrait;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'image',
        'telegram_key_id',
    ];

    /**
     * @var string[]
     */
    protected $appends = ['full_file_path'];

    /**
     * @return BelongsTo
     */
    public function telegramKey(): BelongsTo
    {
        return $this->belongsTo(TelegramKey::class);
    }

}
