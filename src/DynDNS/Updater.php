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

use DynDNS\Updater\Request;
use DynDNS\Updater\ResponseInterface;


class Updater
    implements UpdaterInterface
{
    /**
     * @var ProviderInterface
     */
    protected $provider;

    /**
     * @var ResponseInterface
     */
    protected $response;


    /**
     * __construct()
     *
     * @param ProviderInterface $provider
     */
    public function __construct(ProviderInterface $provider)
    {
        $this->provider = $provider;
    }


    /**
     * Performs the update based on the injected provider
     *
     * @param string $host
     * @param string $ipv4
     * @param string $ipv6
     *
     * @return bool
     */
    public function update($host, $ipv4 = null, $ipv6 = null)
    {
        $request = new Request($host, $ipv4, $ipv6);
        $this->response = $this->provider->update($request);
        return $this->response->getSuccess();
    }


    /**
     * Returns the provider response
     *
     * @return ResponseInterface
     * @throws \RuntimeException When update() wasn't called
     */
    public function getResponse()
    {
        if ($this->response === null) {
            throw new \RuntimeException('You have to use update() before getting a response');
        }
        return $this->response;
    }

} 
