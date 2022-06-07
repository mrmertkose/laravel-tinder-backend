<?php

namespace App\Enums;

enum ActivityTypeEnum : int
{
    const activity = ["right" => 1,"left" => 2,"up" => 0,"down" => 0,"back" => 0];

    const activityStatus = ['like' => 1,'notLike' => 2];
}
