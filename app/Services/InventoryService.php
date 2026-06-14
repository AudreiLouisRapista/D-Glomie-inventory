<?php

namespace App\Services;

class InventoryService
{
    public function resolveStatusId(int $qty): int
    {
        if ($qty <= 0) {
            return 3;
        }

        if ($qty <= 10) {
            return 2;
        }

        return 1;
    }
}