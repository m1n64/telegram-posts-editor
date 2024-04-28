<?php
declare(strict_types=1);

namespace App\Http\Actions\Dashboard;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class IndexAction
{
    /**
     * @param Request $request
     * @return Collection
     */
    public function __invoke(Request $request): \Illuminate\Database\Eloquent\Collection
    {
        return $request->user()->telegramKeys()->with(['channel'])->get();
    }
}
