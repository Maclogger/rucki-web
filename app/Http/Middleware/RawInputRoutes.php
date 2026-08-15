<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;

/**
 * Routes whose request body must reach the application untouched.
 *
 * The global TrimStrings and ConvertEmptyStringsToNull middleware recurse through
 * every string in the request. An rrweb batch is a literal snapshot of the DOM, so
 * trimming would drop the spaces between words in text nodes, and empty nodes would
 * turn into null - which the replayer renders as the literal text "null".
 *
 * Registered in bootstrap/app.php.
 */
final class RawInputRoutes
{
    public const string WEB_RECORDER_BATCH = 'store-web-recorder-batch';

    /**
     * No leading slash - Request::is() matches against the path without one.
     */
    private const array URIS = [
        self::WEB_RECORDER_BATCH,
    ];

    public static function matches(Request $request): bool
    {
        return $request->is(...self::URIS);
    }
}
