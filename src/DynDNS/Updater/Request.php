<?php

/*
* This file is part of the DynDNS library.
*
* (c) Andreas Weber <dialog@andreas-weber.me>
*
* This source file is subject to the MIT license that is bundled
* with this source code in the file LICENSE.
*/

namespace DynDNS\Updater;


class Request
    implements RequestInterface
{
    /**
     * @var string Host
     */
    protected $host;

    /**
     * @var string IPv4
     */
    protected $ipv4;

    /**
     * @var string IPv6
     */
    protected $ipv6;


    /**
     * __construct()
     * You have to pass at least one ip as argument!
     *
     * @param string      $host
     * @param string|null $ipv4
     * @param string|null $ipv6
     *
     * @throws \BadMethodCallException If no ip was given
     * @throws \InvalidArgumentException If a bad host, ipv4 or ipv6 format is given
     */
    public function __construct($host, $ipv4 = null, $ipv6 = null)
    {
        if (!is_string($ipv4) && !is_string($ipv6)) {
            throw new \BadMethodCallException('At least one ip has to be passed');
        }

        if (false === $this->isValidHost($host)) {
            throw new \InvalidArgumentException('Bad host format given: ' . $host);
        }

        if (is_string($ipv4) && false === filter_var($ipv4, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            throw new \InvalidArgumentException('Bad IPv4 format given: ' . $ipv4);
        }

        if (is_string($ipv6) && false === filter_var($ipv6, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            throw new \InvalidArgumentException('Bad IPv6 format given: ' . $ipv6);
        }

        $this->host = (string)$host;
        $this->ipv4 = ($ipv4 !== null) ? (string)$ipv4 : null;
        $this->ipv6 = ($ipv6 !== null) ? (string)$ipv6 : null;
    }


    /**
     * Checks if a given has a valid format
     *
     * @param string $host
     *
     * @return bool
     */
    protected function isValidHost($host)
    {
        return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $host) // valid chars
            && preg_match("/^.{1,253}$/", $host) // overall length
            && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $host)); // length of each label
    }


    /**
     * Returns the host
     *
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }


    /**
     * Returns the IPv4
     *
     * @return string
     */
    public function getIPv4()
    {
        return $this->ipv4;
    }


    /**
     * Returns the IPv6
     *
     * @return string
     */
    public function getIPv6()
    {
        return $this->ipv6;
    }

} 
