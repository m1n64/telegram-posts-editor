<?php

namespace Modules\Posts\Models;

use App\Models\FullPathImageTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attachment extends Model
{
    use HasFactory, SoftDeletes, FullPathImageTrait;

    /**
     * @var string
     */
    protected static string $imageProperty = 'path';

    /**
     * @var string[]
     */
    protected $fillable = [
        'hash',
        'path',
        'post_id',
    ];

    /**
     * @var string[]
     */
    protected $appends = ['full_file_path'];

    /**
     * @return BelongsTo
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
