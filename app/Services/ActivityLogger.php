<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    /**
     * Record an administrative activity.
     */
    public function log(
        string $action,
        string $description,
        ?Model $subject = null,
        array $properties = [],
        ?User $actor = null
    ): ActivityLog {
        $actor ??= Auth::user();

        $request = app()->bound('request')
            ? request()
            : null;

        return ActivityLog::create([
            'user_id' => $actor?->id,
            'actor_name' => $actor?->name,
            'action' => $action,
            'description' => $description,
            'subject_type' => $subject?->getMorphClass(),
            'subject_id' => $subject?->getKey(),
            'properties' => $this->filterSensitiveData($properties),
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
        ]);
    }

    /**
     * Remove sensitive fields from activity properties.
     */
    private function filterSensitiveData(array $properties): array
    {
        $sensitiveKeys = [
            'password',
            'password_confirmation',
            'remember_token',
            'token',
            'secret',
            'api_key',
            'authorization',
            'credentials',
        ];

        foreach ($properties as $key => $value) {
            if (in_array(strtolower((string) $key), $sensitiveKeys, true)) {
                unset($properties[$key]);
                continue;
            }

            if (is_array($value)) {
                $properties[$key] = $this->filterSensitiveData($value);
            }
        }

        return $properties;
    }
}