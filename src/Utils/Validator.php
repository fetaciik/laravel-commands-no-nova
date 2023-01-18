<?php

namespace Wame\LaravelCommands\Utils;

use Wame\ApiResponse\Helpers\ApiResponse;

class Validator
{
    /**
     * @var string|null
     */
    protected static ?string $code = null;

    /**
     * @var string|null
     */
    protected static ?string $prefix = null;

    /**
     * @var int|null
     */
    protected static ?int $statusCode = null;

    /**
     * @param string $code
     * @param string $prefix
     * @return static
     */
    public static function code(string $code, string $prefix = 'api'): static
    {
        static::$code = $code;
        static::$prefix = $prefix;

        return new static;
    }

    /**
     * @param int $statusCode
     * @return static
     */
    public static function statusCode(int $statusCode = 400): static
    {
        static::$statusCode = $statusCode;

        return new static;
    }

    /**
     * @param array $data
     * @param array $rules
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public static function validate(array $data, array $rules): bool|\Illuminate\Http\JsonResponse
    {
        $validator = \Illuminate\Support\Facades\Validator::make($data, $rules);

        if ($validator->fails()) {
            return ApiResponse::errors($validator->messages()->toArray())
                ->code(static::$code, static::$prefix)
                ->response(static::$statusCode);
        } else {
            return true;
        }
    }
}
