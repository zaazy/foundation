<?php

declare(strict_types=1);

namespace Zaazy\Foundation\Traits;

trait CastAuditablesTrait
{
    /**
     * @param bool $softDeletes
     * @return array $casts
     */
    public function getAuditableCasts($softDeletes = false): array
    {
        $types = ['created', 'updated', 'deleted'];
        $casts = [];
        foreach ($types as $type) {
            if ($type === 'deleted') {
                if (! $softDeletes) {
                    continue;
                }
            }
            $casts["{$type}_at"] = 'datetime';
            $casts["{$type}_by_id"] = 'integer';
            $casts["{$type}_by_type"] = 'string';
        }

        return $casts;
    }
}
