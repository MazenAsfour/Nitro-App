<?php

namespace App\Services;

interface UserServiceInterface
{
    /**
     * Generate hash for the given string.
     *
     * @param  string $password
     * @return string
     */
    public function hash(string $password): string;
}


?>