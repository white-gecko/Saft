<?php
require_once 'Mimeparse.php';

class Saft_Helper_MimetypeHelper extends Saft_Helper
{
    /**
     * Matches an array of mime types against the Accept header in a request.
     *
     * @param Saft_Request $request the request
     * @param array $supportedMimetypes The mime types to match against
     * @return string
     */
    public static function matchFromRequest (Saft_Request $request, array $supportedMimetypes)
    {
        // get accept header
        $header = $request->getHeader();
        if (isset($header['Accept']) && !empty($header['Accept'])) {
            $acceptHeader = strtolower($header['Accept']);
        } else {
            $acceptHeader = '*/*';
        }

        try {
            // suppress warnings because we will catch exceptions
            $match = @Mimeparse::best_match($supportedMimetypes, $acceptHeader);
        } catch (Exception $e) {
            $match = '';
        }

        return $match;
    }
}
