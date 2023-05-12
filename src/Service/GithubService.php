<?php

namespace App\Service;

use App\Enum\healthStatus;

class GithubService
{
    public function getHealthReport(string $dinosaurName): healthStatus
    {
        return healthStatus::HEALTHY;
    }

}
