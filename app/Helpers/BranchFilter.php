<?php

namespace App\Helpers;

class BranchFilter
{
    public static function apply($query, string $table): mixed
    {
        // SuperAdmin — no filter
        if (session('user_role') === 'SuperAdmin') {
            return $query;
        }

        return $query->where("{$table}.branch_id", session('branch_id'));
    }
}