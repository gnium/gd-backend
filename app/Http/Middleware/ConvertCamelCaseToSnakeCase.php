<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
class ConvertCamelCaseToSnakeCase
{
    public function handle(Request $request, Closure $next)
    {
        $convertedData = $this->convertArrayKeysToSnakeCase($request->all());
        Log::info('🔍 Incoming Request:', [
            'original' => $request->all(),
            'converted' => $convertedData
        ]);
    
        $request->replace($convertedData);

        return $next($request);
    }

    private function convertArrayKeysToSnakeCase($data)
    {
        if (!is_array($data)) {
            return $data;
        }

        $converted = [];
        foreach ($data as $key => $value) {
            $newKey = Str::snake($key);
            $converted[$newKey] = is_array($value) ? $this->convertArrayKeysToSnakeCase($value) : $value;
        }

        return $converted;
    }
}
