<?php
declare(strict_types=1);

namespace App\Http\Responses;

use App\DTO\ErrorResponseDTO;
use App\Http\Enums\HttpStatusesEnum;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

readonly class ErrorJsonResponse implements Responsable
{
    /**
     * @param string $message
     * @param mixed|null $data
     * @param HttpStatusesEnum $status
     */
    public function __construct(
        public string           $message,
        public mixed            $data = null,
        public HttpStatusesEnum $status = HttpStatusesEnum::INTERNAL_SERVER_ERROR
    )
    {
    }

    /**
     * @param $request
     * @return JsonResponse
     */
    public function toResponse($request): JsonResponse
    {
        return new JsonResponse(
            data: new ErrorResponseDTO($this->message, $this->data),
            status: $this->status->value
        );
    }
}
