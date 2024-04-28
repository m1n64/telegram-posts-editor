<?php
declare(strict_types=1);

namespace Modules\Posts\Services;

use Illuminate\Http\UploadedFile;
use Modules\Posts\Models\Attachment;

class AttachmentService
{
    /**
     * @param int $postId
     * @param array<UploadedFile> $attachments
     * @return void
     */
    public function updateAttachments(int $postId, array $attachments): void
    {
        $newAttachmentCount = count($attachments);

        if ($newAttachmentCount === 0) {
            return;
        }

        $existingAttachmentCount = Attachment::where('post_id', $postId)->count();

        if ($newAttachmentCount !== $existingAttachmentCount) {
            Attachment::where('post_id', $postId)->delete();

            foreach ($attachments as $attachment) {
                $hash = $this->hashFile($attachment);
                $filePath = $this->putFile($postId, $hash, $attachment);

                Attachment::create([
                    'hash' => $hash,
                    'path' => $filePath,
                    'post_id' => $postId,
                ]);
            }
        } else {
            foreach ($attachments as $attachment) {
                $hash = $this->hashFile($attachment);

                $existingAttachment = Attachment::where('hash', $hash)->where('post_id', $postId)->first();

                if (!$existingAttachment) {
                    $filePath = $this->putFile($postId, $hash, $attachment);

                    Attachment::create([
                        'hash' => $hash,
                        'path' => $filePath,
                        'post_id' => $postId,
                    ]);
                }
            }
        }
    }

    /**
     * @param int $postId
     * @param string $fileName
     * @param UploadedFile $file
     * @return string
     */
    protected function putFile(int $postId, string $fileName, UploadedFile $file): string
    {
        $filePath = "posts/$postId/attachments/";
        $file->storeAs("public/$filePath", $fileName);
        return $filePath.$fileName;
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    protected function hashFile(UploadedFile $file): string
    {
        return md5_file($file->getRealPath());
    }
}
