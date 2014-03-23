<?php

/*
* This file is part of the DynDNS library.
*
* (c) Andreas Weber <dialog@andreas-weber.me>
*
* This source file is subject to the MIT license that is bundled
* with this source code in the file LICENSE.
*/

namespace DynDNS;

use DynDNS\Updater\ResponseInterface;


interface UpdaterInterface
{
    /**
     * Performs the update based on the injected provider
     *
     * @param string $host
     * @param string $ipv4
     * @param string $ipv6
     *
     * @return bool
     */
    public function update($host, $ipv4 = null, $ipv6 = null);


    /**
     * Returns the provider response
     *
     * @return ResponseInterface
     * @throws \RuntimeException When update() wasn't called
     */
    public function getResponse();
} 
