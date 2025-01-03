<?php

namespace Izopi4a\EcontBundle\Service;

class EcontService
{
    private string $user;
    private string $password;
    private string $locale;
    public function __construct(string $user, string $password, string $locale)
    {
        $this->user = $user;
        $this->password = $password;
        $this->locale = $locale;
    }
}