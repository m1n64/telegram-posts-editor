<?php
declare(strict_types=1);

namespace App\Http\Responses;

use App\DTO\SuccessResponseDTO;
use App\Http\Enums\HttpStatusesEnum;
use Illuminate\Contracts\Support\Responsable;
use Symfony\Component\HttpFoundation\JsonResponse;

readonly class SuccessJsonResponse implements Responsable
{
    /**
     * @param mixed $data
     * @param HttpStatusesEnum $status
     */
    function __construct(
        public mixed            $data,
        public HttpStatusesEnum $status = HttpStatusesEnum::OK
    )
    {}

    /**
     * @param $request
     * @return JsonResponse
     */
    public function toResponse($request): JsonResponse
    {
        return new JsonResponse(
            data: new SuccessResponseDTO($this->data),
            status: $this->status->value,
        );
    }
}
