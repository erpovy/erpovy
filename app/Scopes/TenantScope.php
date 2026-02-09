<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class TenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    /**
     * Prevent infinite recursion when checking user.
     */
    protected static $isCheckingUser = false;

    public function apply(Builder $builder, Model $model): void
    {
        if (self::$isCheckingUser) {
            return;
        }

        self::$isCheckingUser = true;

        try {
            if (Auth::hasUser()) {
                $user = Auth::user();

                // 1. Check if SuperAdmin is inspecting a company
                if (session()->has('is_inspecting') && session()->has('inspected_company_id')) {
                    $builder->where($model->getTable() . '.company_id', '=', session('inspected_company_id'));
                    return;
                }

                // 2. Regular user scoping
                if ($user && $user->company_id) {
                    $builder->where($model->getTable() . '.company_id', '=', $user->company_id);
                }
            }
        } catch (\Throwable $e) {
            // Silent failure
        } finally {
            self::$isCheckingUser = false;
        }
    }
}
