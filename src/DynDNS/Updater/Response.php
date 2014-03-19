<?php

/*
* This file is part of the DynDNS library.
*
* (c) Andreas Weber <weber@webmanufaktur-weber.de>
*
* This source file is subject to the MIT license that is bundled
* with this source code in the file LICENSE.
*/

namespace DynDNS\Updater;


class Response
    implements ResponseInterface
{
    /**
     * @var bool Successful request
     */
    protected $success;

    /**
     * @var string Raw provider response
     */
    protected $rawResponse;


    /**
     * __construct()
     *
     * @param bool        $success
     * @param string|null $rawResponse
     */
    public function __construct($success, $rawResponse = null)
    {
        $this->success = (bool)$success;
        $this->rawResponse = $rawResponse;
    }


    /**
     * Returns boolean true if update was successful
     *
     * @return bool
     */
    public function getSuccess()
    {
        return $this->success;
    }


    /**
     * Returns the raw response
     *
     * @return string
     */
    public function getRawResponse()
    {
        return $this->rawResponse;
    }

} 
