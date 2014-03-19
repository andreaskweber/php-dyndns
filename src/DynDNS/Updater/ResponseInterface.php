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


interface ResponseInterface
{
    /**
     * Returns boolean true if update was successful
     *
     * @return bool
     */
    public function getSuccess();


    /**
     * Returns the raw response
     *
     * @return string
     */
    public function getRawResponse();
} 
