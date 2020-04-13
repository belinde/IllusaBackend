<?php
namespace App\Exception;

use RuntimeException;
use Symfony\Component\HttpFoundation\Exception\RequestExceptionInterface;

/**
 * Class InvalidJsonContentException
 * @package App\Exception
 */
class InvalidJsonContentException extends RuntimeException implements RequestExceptionInterface
{

}
