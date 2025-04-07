<?php

namespace App\Exceptions;

use Exception as BaseException;
use Illuminate\Contracts\Debug\ShouldntReport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class Exception extends BaseException implements ShouldntReport
{
    /** The HTTP status code to return with the exception */
    protected int $statusCode;

    /** The errors included in the HTTP response */
    protected array $errors;

    public function __construct(
        string $message = '',
        int $code = 0,
        ?Throwable $previous = null,
        int $statusCode = 500,
        array $errors = [],
    ) {
        parent::__construct($message, $code, $previous);

        $this->statusCode = $statusCode;
        $this->errors = $errors;
    }

    /**
     * Get the HTTP status code to return with the exception.
     *
     * @return int The HTTP status code to return with the exception.
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Get the errors included in the HTTP response.
     *
     * @return array<string, mixed> The errors included in the HTTP response.
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Render the exception as a JSON response.
     *
     * @param  Request  $request  The HTTP request that caused the exception.
     * @return JsonResponse|bool The HTTP response to return, or false if not applicable.
     */
    public function render(Request $request): JsonResponse|bool
    {
        if ($request->expectsJson()) {
            $data = [
                'message' => $this->getMessage(),
            ];

            if ($this->getCode() !== 0) {
                $data['code'] = $this->getCode();
            }

            if (! empty($this->getErrors())) {
                $data['errors'] = $this->getErrors();
            }

            return response()->json($data, $this->statusCode);
        }

        return false;
    }
}
