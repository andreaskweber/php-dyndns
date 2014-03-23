<?php

/*
* This file is part of the DynDNS library.
*
* (c) Andreas Weber <dialog@andreas-weber.me>
*
* This source file is subject to the MIT license that is bundled
* with this source code in the file LICENSE.
*/

namespace DynDNS\Helper;


trait Host
{
    /**
     * Splits the given host to a subdomain, an hostname and a TLD.
     *
     * @param string $host
     *
     * @return array
     * @throws \InvalidArgumentException When a bad host is given
     */
    public function split($host)
    {
        $splitted = explode('.', $host);
        if (count($splitted) < 3) {
            throw new \InvalidArgumentException('A host must have at least a subdomain, an hostname and a TLD');
        }

        $host = array(
            'tld'       => array_pop($splitted),
            'hostname'  => array_pop($splitted),
            'subdomain' => implode('.', $splitted)
        );

        return array_reverse($host, true); // reverse, semantic purpose only :)
    }
    
} 
