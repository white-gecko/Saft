<?php
/**
 * This file is part of the {@link https://github.com/white-gecko/Saft Saft} project.
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU General Public License (GPL)
 */

/**
 * The Url provides methods to add ne parameters to a URL
 */
class Saft_Url
{
    private $_baseUrl;

    private $_parameters;

    /**
     * The constructor takes
     * @param $request eighter a base URL (string) or a Request object (Saft_Request)
     */
    public function __construct ($request)
    {
        if (is_string($request)) {
            $this->_baseUrl = $request;
        } else if (is_a($request, 'Saft_Request')) {
            $this->_baseUrl = $request->getBaseUri();
            $this->_parameters = $request->getParameters();
        }
    }

    /**
     * Set a parameter, if it already exists, it will be overwritten
     */
    public function setParameter ($key, $value)
    {
        $key = Saft_Request::replaceKey($key);
        $this->_parameters[$key] = $value;
    }

    /**
     * Build the string URL containing the specified parameters
     */
    public function getUrl ()
    {
        return $this->_baseUrl . '?' . http_build_query($this->_parameters);
    }

    public function __toString ()
    {
        return $this->getUrl();
    }
}
