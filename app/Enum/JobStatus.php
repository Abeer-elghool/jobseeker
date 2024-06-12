<?php


namespace App\Enum;

use App\Traits\Enums\EnumConverter;

enum JobStatus: string
{
    use EnumConverter;

    const OPEN = 'open';
    const CLOSED = 'closed';
    const PENDING = 'pending';
}
