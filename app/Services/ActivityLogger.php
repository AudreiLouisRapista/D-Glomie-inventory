<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Session;

class ActivityLogger
{
    public function log(string $action, string $description): void
    {
        ActivityLog::create([
            'admin_id' => Session::get('id'),
            'action' => $action,
            'description' => $description,
        ]);
    }
}
