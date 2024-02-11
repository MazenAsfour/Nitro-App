<?php

namespace App\Listeners;

use App\Events\UserSaved;
use App\Services\UserService;

class SaveUserBackgroundInformation
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function handle(UserSaved $event)
    {
        $this->userService->saveUserDetails($event->user);
    }
}
