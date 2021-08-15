<?php

namespace App\Services\FileUpload\Exceptions;

use Exception;
use JetBrains\PhpStorm\Pure;
use Throwable;

class FilePrivacyException extends Exception
{

    #[Pure]
    public function __construct($message = "Privacy can only be 'public' or 'private'.", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
