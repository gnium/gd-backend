<?php

namespace App\Traits;
use Illuminate\Database\Eloquent\Factories\HasFactory as BaseHasFactory;

trait HasModuleFactory
{
    use BaseHasFactory;
    /**
     * Specify the factory associated with this model.
     */
    protected static function newFactory()
    {
        // Dynamically resolve the factory class based on the model's namespace
        $factoryClass = static::guessFactoryClass();
        
        if (class_exists($factoryClass)) {
            return $factoryClass::new();
        }

        throw new \RuntimeException("Factory class {$factoryClass} not found for model " . static::class);
    }

    /**
     * Guess the factory class name based on the model's namespace.
     */
    protected static function guessFactoryClass(): string
    {
        return str_replace(
            ['\\Models\\', '\\Models'],
            '\\Database\\Factories\\',
            static::class
        ) . 'Factory';
    }
}
