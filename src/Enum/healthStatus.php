<?php

namespace App\Enum;

enum healthStatus: string
{
    case HEALTHY = 'Healthy';
    case SICK = 'Sick';
    case HUNGRY = 'Hungry';
}
