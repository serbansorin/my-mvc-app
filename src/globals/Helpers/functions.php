<?php

use Hyperf\Database\Model\Model;
use Swoole\Http\Request;
use Swoole\Http\Response;

// use Illuminate\Http\Request as IlluminateRequest;
// use Illuminate\Http\Response as IlluminateResponse;



function app(string $key = null)
{
    // Implement your logic to get the application instance here

    $app = \Kernel\Application::getInstance();

    return $key ? $app->get($key) : $app;
}

/**
 * Get the Swoole HTTP request object.
 *
 * @return Request
 */
function request(): Request
{
    return app('request');
}

/**
 * Get the Swoole HTTP response object.
 *
 * @return Response
 */
function response(): Response
{
    return app('response');
}

/**
 * Convert Swoole request to Illuminate request.
 *
 * @param Request $swooleRequest
 * @return IlluminateRequest
 */
// function convertToIlluminateRequest(Request $swooleRequest): IlluminateRequest
// {
//     $tmpRequest = new IlluminateRequest(
//         $swooleRequest->get ?? [],
//         $swooleRequest->post ?? [],
//         [],
//         $swooleRequest->cookie ?? [],
//         $swooleRequest->files ?? [],
//         $swooleRequest->server ?? [],
//         $swooleRequest->rawContent()
//     );
//     return $tmpRequest;
// }

/**
 * Convert Swoole response to Illuminate response.
 *
 * @param Response $swooleResponse
 * @return IlluminateResponse
 */
// function convertToIlluminateResponse(Response $swooleResponse): IlluminateResponse
// {
//     // Implement your conversion logic here
// }

/**
 * Get the current user.
 *
 * @return User|null
 */
function user(): ?App\Models\User
{
    // Implement your logic to retrieve the current user here
    return null;
}

// /**
//  * Check if the current user is authenticated.
//  *
//  * @return bool
//  */
// function auth(): bool
// {
//     // Implement your logic to check if the user is authenticated here
// }

// /**
//  * Generate a random string.
//  *
//  * @param int $length
//  * @return string
//  */
// function generateRandomString(int $length): string
// {
//     // Implement your logic to generate a random string here
// }

// /**
//  * Encrypt the given value.
//  *
//  * @param string $value
//  * @return string
//  */
// function encrypt(string $value): string
// {
//     // Implement your logic to encrypt the value here
// }

// /**
//  * Decrypt the given value.
//  *
//  * @param string $value
//  * @return string
//  */
// function decrypt(string $value): string
// {
//     // Implement your logic to decrypt the value here
// }

/**
 * Get the current timestamp.
 *
 * @return int
 */
function timestamp(): int
{
    return time();
}

/**
 * Get the current date.
 *
 * @return string
 */
function currentDate(): string
{
    return date('Y-m-d');
}

/**
 * Get the current time.
 *
 * @return string
 */
function currentTime(): string
{
    return date('H:i:s');
}

/**
 * Get the current datetime.
 *
 * @return string
 */
function currentDateTime(): string
{
    return date('Y-m-d H:i:s');
}

/**
 * Check if a value is empty.
 *
 * @param mixed $value
 * @return bool
 */
function isEmpty($value): bool
{
    return empty($value);
}

/**
 * Check if a value is null.
 *
 * @param mixed $value
 * @return bool
 */
function isNull($value): bool
{
    return is_null($value);
}

/**
 * Check if a value is numeric.
 *
 * @param mixed $value
 * @return bool
 */
function isNumeric($value): bool
{
    return is_numeric($value);
}

/**
 * Check if a value is a string.
 *
 * @param mixed $value
 * @return bool
 */
function isString($value): bool
{
    return is_string($value);
}

/**
 * Check if a value is an array.
 *
 * @param mixed $value
 * @return bool
 */
function isArray($value): bool
{
    return is_array($value);
}

/**
 * Check if a value is an object.
 *
 * @param mixed $value
 * @return bool
 */
function isObject($value): bool
{
    return is_object($value);
}

/**
 * Check if a value is a boolean.
 *
 * @param mixed $value
 * @return bool
 */
function isBoolean($value): bool
{
    return is_bool($value);
}

/**
 * Check if a value is an integer.
 *
 * @param mixed $value
 * @return bool
 */
function isInteger($value): bool
{
    return is_int($value);
}

/**
 * Check if a value is a float.
 *
 * @param mixed $value
 * @return bool
 */
function isFloat($value): bool
{
    return is_float($value);
}

/**
 * Check if a value is a resource.
 *
 * @param mixed $value
 * @return bool
 */
function isResource($value): bool
{
    return is_resource($value);
}

/**
 * Check if a value is a callable.
 *
 * @param mixed $value
 * @return bool
 */
function isCallable($value): bool
{
    return is_callable($value);
}

/**
 * Check if a value is an instance of a class.
 *
 * @param mixed $value
 * @param string $class
 * @return bool
 */
function isInstanceOf($value, string $class): bool
{
    return $value instanceof $class;
}

/**
 * Check if a value is equal to another value.
 *
 * @param mixed $value1
 * @param mixed $value2
 * @return bool
 */
function isEqual($value1, $value2): bool
{
    return $value1 == $value2;
}

/**
 * Check if a value is identical to another value.
 *
 * @param mixed $value1
 * @param mixed $value2
 * @return bool
 */
function isIdentical($value1, $value2): bool
{
    return $value1 === $value2;
}

/**
 * Pluralize: get the plural form of a word.
 * @param string $word
 * @return string
 */
function pluralize(string $word): string
{
    $irregulars = [
        'child' => 'children',
        'goose' => 'geese',
        'man' => 'men',
        'woman' => 'women',
        'tooth' => 'teeth',
        'foot' => 'feet',
    ];

    if (array_key_exists(strtolower($word), $irregulars)) {
        return $irregulars[strtolower($word)];
    }

    $lastLetter = substr($word, -1);
    if ($lastLetter === 'y') {
        return substr($word, 0, -1) . 'ies';
    } elseif (in_array($lastLetter, ['s', 'x', 'z']) || substr($word, -2) === 'ch' || substr($word, -2) === 'sh') {
        return $word . 'es';
    } else {
        return $word . 's';
    }
}

function singularize(string $word): string
{
    $irregulars = [
        'children' => 'child',
        'geese' => 'goose',
        'men' => 'man',
        'women' => 'woman',
        'teeth' => 'tooth',
        'feet' => 'foot',
    ];

    if (array_key_exists(strtolower($word), $irregulars)) {
        return $irregulars[strtolower($word)];
    }

    if (substr($word, -3) === 'ies') {
        return substr($word, 0, -3) . 'y';
    } elseif (substr($word, -2) === 'es') {
        $base = substr($word, 0, -2);
        if (in_array(substr($base, -1), ['s', 'x', 'z']) || substr($base, -2) === 'ch' || substr($base, -2) === 'sh') {
            return $base;
        }
    }

    if (substr($word, -1) === 's' && substr($word, -2) !== 'ss') {
        return substr($word, 0, -1);
    }

    return $word;
}