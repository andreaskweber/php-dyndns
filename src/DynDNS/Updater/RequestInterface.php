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


interface RequestInterface
{
    /**
     * Returns the host
     *
     * @return string
     */
    public function getHost();


    /**
     * Returns the IPv4
     *
     * @return string
     */
    public function getIPv4();


    /**
     * Returns the IPv6
     *
     * @return string
     */
    public function getIPv6();
} 
