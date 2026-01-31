<?php

namespace App\Events;

class DashboardNotificationRequested
{
    public function __construct(
        public string $permission,   
        public string $title,
        public string $body,
        public array $data = [],
    ) {}
}
