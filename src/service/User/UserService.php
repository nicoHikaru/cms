<?php
namespace App\Service\User;

use App\Entity\User;
use App\Repository\UserRepository;


class UserService
{
    private $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function findById(int $id)
    {
        return $this->userRepository->findOneBy(array('id' => $id));
    }

    public function setRoles(array $roles,User $user) {
        return $this->userRepository->setRoles($roles,$user);
    }
}