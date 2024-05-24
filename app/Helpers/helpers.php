<?php

namespace App\Helpers;

use App\Models\Option;

class OptionsTableHelper
{
    public static function getOption(string $name): string
    {
        $option = Option::where('name', $name)->first();
        if ($option) {
            return $option->value;
        }

        return '';
    }

    public static function setOption(string $name, mixed $value): void
    {
        $option = Option::where('name', $name)->first();

        if ($option) {
            $option->value = $value;
            $option->save();
        } else {
            Option::create([
                'name' => $name,
                'value' => $value,
            ]);
        }
    }

    public static function deleteOption(string $name): void
    {
        $option = Option::where('name', $name)->first();

        if ($option) {
            $option->delete();
        }
    }

    public static function optionExists(string $name): bool
    {
        $option = Option::where('name', $name)->first();

        return (bool) $option;
    }
}
