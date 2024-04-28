<?php
namespace Modules\Auth\Traits;

use App\Models\User;

trait UseTokenTrait
{
    /**
     * @param User $user
     * @return string
     */
    public function createToken(User $user): string
    {
        $user->tokens()->delete();
        return $user->createToken('client')->plainTextToken;
    }
}
