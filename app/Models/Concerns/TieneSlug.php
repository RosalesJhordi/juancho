<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

trait TieneSlug
{
    /** Genera un slug único a partir de un texto. */
    public static function generarSlug(string $base, ?int $ignorarId = null): string
    {
        $original = Str::slug($base) ?: 'item';
        $slug = $original;
        $i = 2;

        while (
            static::query()
                ->where('slug', $slug)
                ->when($ignorarId, fn ($q) => $q->where('id', '!=', $ignorarId))
                ->exists()
        ) {
            $slug = $original . '-' . $i++;
        }

        return $slug;
    }
}
