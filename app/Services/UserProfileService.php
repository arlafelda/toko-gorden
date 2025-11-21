<?php

namespace App\Services;

use App\Models\User;

class UserProfileService
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function saveAddress(array $data)
    {
        return $this->user->update($data);
    }
}
