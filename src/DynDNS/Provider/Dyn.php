<?php

/*
* This file is part of the DynDNS library.
*
* (c) Andreas Weber <weber@webmanufaktur-weber.de>
*
* This source file is subject to the MIT license that is bundled
* with this source code in the file LICENSE.
*/

namespace DynDNS\Provider;

use DynDNS\ProviderInterface;
use DynDNS\Updater\RequestInterface;
use DynDNS\Updater\Response;
use DynDNS\Updater\ResponseInterface;


class Dyn
    implements ProviderInterface
{
    /**
     * @var string User
     */
    protected $user;

    /**
     * @var string Password
     */
    protected $password;


    /**
     * __construct()
     *
     * @param string $user
     * @param string $password
     */
    public function __construct($user, $password)
    {
        $this->user = (string)$user;
        $this->password = (string)$password;
    }


    /**
     * Performs the update
     *
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     * @throws \RuntimeException When ipv6 address is given
     * @throws \RuntimeException When no ipv4 address is passed
     */
    public function update(RequestInterface $request)
    {
        if (null === $request->getIPv4()) {
            throw new \RuntimeException('You have to pass an ipv4 address');
        }

        if ($request->getIPv6()) {
            throw new \RuntimeException('Cannot use ipv6 with dyn.com provider');
        }

        $url = sprintf(
            'https://%s:%s@members.dyndns.org/nic/update?hostname=%s&myip=%s&wildcard=NOCHG&mx=NOCHG&backmx=NOCHG',
            $this->user,
            $this->password,
            $request->getHost(),
            $request->getIPv4()
        );

        $curl = curl_init($url);
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_USERAGENT      => 'PHP-DynDNS - Library - 1.0'
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        if (false !== strpos($response, 'good')) {
            return new Response(true, $response);
        } else {
            return new Response(false, $response);
        }
    }

} 
