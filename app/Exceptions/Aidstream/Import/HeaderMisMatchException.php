<?php namespace App\Exceptions\Aidstream\Import;

use Exception;

/**
 * Class HeaderMisMatchException
 */
class HeaderMisMatchException extends Exception
{
    /**
     * HeaderMisMatchException constructor.
     * @param string $message
     */
    public function __construct($message)
    {
        $this->message = $message;
    }
}
