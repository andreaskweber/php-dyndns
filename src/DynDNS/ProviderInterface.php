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

use DynDNS\Updater\RequestInterface;
use DynDNS\Updater\ResponseInterface;


interface ProviderInterface
{
    /**
     * Performs the update
     *
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     */
    public function update(RequestInterface $request);
} 
