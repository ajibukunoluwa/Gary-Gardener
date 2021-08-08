<?php

namespace App\Exceptions;

use Exception;

class AbortJsonException extends Exception
{

    protected $message;
    protected $statusCode;

    public function __construct(string $message, int $statusCode)
    {
        $this->message = $message;
        $this->statusCode = $statusCode;
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return json([], $this->message, $this->statusCode);
    }
}
