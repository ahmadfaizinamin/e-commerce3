<?php
namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Override;

class UserRepository implements UserRepositoryInterface
{
    #[Override]
    public function create(array $data)
    {
        return User::create($data);
    }
}