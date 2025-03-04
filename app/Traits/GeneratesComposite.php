<?php

namespace App\Traits;

trait GeneratesComposite
{
    /**
     * Boot the trait to generate the composite field.
     */
    public static function bootGeneratesComposite()
    {
        static::saving(function ($model) {
            if (method_exists($model, 'getCompositeFormat')) {
                $model->composite = $model->generateComposite();
            }
        });
    }

    /**
     * Generate the composite field based on the format defined in the model.
     *
     * @return string
     */
    public function generateComposite(): string
    {
        $format = $this->getCompositeFormat();

        return preg_replace_callback('/{(\w+)}/', function ($matches) {
            $field = $matches[1];
            return $this->{$field} ?? '';
        }, $format);
    }

    /**
     * Define the composite format in the model.
     *
     * @return string
     */
    abstract public function getCompositeFormat(): string;
}
