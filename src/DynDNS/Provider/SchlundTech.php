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
use DynDNS\Helper\Host;


class SchlundTech
    implements ProviderInterface
{
    use Host;

    const HOST = 'https://gateway.schlundtech.de';

    /**
     * @var string Gateway-User
     */
    protected $user;

    /**
     * @var string Gateway-Password
     */
    protected $password;

    /**
     * @var string Gateway-Context
     */
    protected $context;


    /**
     * __construct()
     *
     * @param string $user
     * @param string $password
     * @param string $context
     */
    function __construct($user, $password, $context)
    {
        $this->user = (string)$user;
        $this->password = (string)$password;
        $this->context = (string)$context;
    }


    /**
     * Performs the update
     *
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     * @throws \RuntimeException
     */
    public function update(RequestInterface $request)
    {
        /**
         * Zone inquire
         */

        $domInquire = $this->loadXml($this->getRequestGet());

        $splittedHost = $this->split($request->getHost());
        $name = $splittedHost['hostname'] . '.' . $splittedHost['tld'];

        $domInquire->getElementsByTagName('user')->item(0)->nodeValue = $this->user;
        $domInquire->getElementsByTagName('password')->item(0)->nodeValue = $this->password;
        $domInquire->getElementsByTagName('context')->item(0)->nodeValue = $this->context;
        $domInquire->getElementsByTagName('name')->item(0)->nodeValue = $name;

        $response = $this->requestCurl($domInquire->saveXML());
        $domResponse = $this->loadXml($response);

        /**
         * Check if we can get zone records
         */

        $xpath = new \DOMXPath($domResponse);
        $nodeList = $xpath->query('/response/result/status/type');

        if (0 === $nodeList->length) {
            throw new \RuntimeException('Query path in response not found. Maybe the api changed?');
        }

        if ('success' !== $nodeList->item(0)->nodeValue) {
            throw new \RuntimeException('Cannot get zone records. ' . $xpath->query('/response/result/status/text')->item(0)->nodeValue);
        }

        /**
         * Zone-Update
         */

        $domUpdate = $this->loadXml($this->getRequestPut());

        $domUpdateZone = $domResponse->getElementsByTagName('zone')->item(0);

        $domUpdateZone->removeChild($domUpdateZone->getElementsByTagName('created')->item(0));
        $domUpdateZone->removeChild($domUpdateZone->getElementsByTagName('changed')->item(0));
        $domUpdateZone->removeChild($domUpdateZone->getElementsByTagName('domainsafe')->item(0));
        $domUpdateZone->removeChild($domUpdateZone->getElementsByTagName('owner')->item(0));
        $domUpdateZone->removeChild($domUpdateZone->getElementsByTagName('updated_by')->item(0));

        $domUpdate->getElementsByTagName('task')->item(0)->appendChild(
            $domUpdate->importNode($domUpdateZone, true)
        );

        $domUpdate->getElementsByTagName('user')->item(0)->nodeValue = $this->user;
        $domUpdate->getElementsByTagName('password')->item(0)->nodeValue = $this->password;
        $domUpdate->getElementsByTagName('context')->item(0)->nodeValue = $this->context;

        /**
         * Populate Zone-Update-XML
         */

        $subdomain = $splittedHost['subdomain'];

        if ($request->getIPv4()) {
            $xpath = new \DOMXPath($domUpdate);
            $query = "//task/zone/rr[name='" . $subdomain . "' and type='A']/value";
            $entries = $xpath->query($query);

            if ($entries->length !== 1) {
                throw new \RuntimeException('Domain has no A-record for ' . $subdomain);
            }

            $entries->item(0)->nodeValue = $request->getIPv4();
        }

        if ($request->getIpv6()) {
            $xpath = new \DOMXPath($domUpdate);
            $query = "//task/zone/rr[name='" . $subdomain . "' and type='AAAA']/value";
            $entries = $xpath->query($query);

            if ($entries->length !== 1) {
                throw new \RuntimeException('Domain has no AAAA-record for ' . $subdomain);
            }

            $entries->item(0)->nodeValue = $request->getIpv6();
        }

        /**
         * Update-Request
         */

        $response = $this->requestCurl($domUpdate->saveXML());
        $domResponse = $this->loadXml($response);

        $xpath = new \DOMXPath($domResponse);
        $nodeList = $xpath->query('/response/result/status/type');

        if (0 === $nodeList->length) {
            throw new \RuntimeException('Query path in response not found. Maybe the api changed?');
        }

        if ('success' === $nodeList->item(0)->nodeValue) {
            $response = new Response(true, $domResponse->saveXML());
            return $response;
        } else {
            throw new \RuntimeException('Cannot set new zone records. ' . $xpath->query('/response/result/status/text')->item(0)->nodeValue);
        }

    }


    /**
     * Loads a XML-String
     *
     * @param string $xml
     *
     * @return \DOMDocument
     */
    protected function loadXml($xml)
    {
        $dom = new \DOMDocument();
        $dom->loadXML((string)$xml);
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;

        return $dom;
    }


    /**
     * Curl-Wrapper
     *
     * @param mixed $data
     *
     * @return mixed
     * @throws \RuntimeException
     */
    function requestCurl($data)
    {
        $curl = curl_init(self::HOST);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        if (false === ($response = curl_exec($curl))) {
            throw new \RuntimeException('Could\'t process curl request');
        }

        curl_close($curl);
        return $response;
    }


    /**
     * SchlundTech-Gateway-Handbook v2
     * 5.6 Zone Inquire, Page 64
     *
     * @return string
     */
    protected function getRequestGet()
    {
        return '<?xml version="1.0" encoding="UTF-8"?>
                <request>
                    <language>en</language>
                    <auth>
                        <user></user>
                        <password></password>
                        <context></context>
                    </auth>                    
                    <task>
                        <code>0205</code>
                        <zone>
                            <name></name>
                        </zone>
                    </task>
                </request>';
    }


    /**
     * SchlundTech-Gateway-Handbook v2
     * 5.3 Zone-Update, Page 59
     *
     * @return string
     */
    protected function getRequestPut()
    {
        return '<?xml version="1.0" encoding="UTF-8"?>
                <request>
                    <language>en</language>
                    <auth>
                        <user></user>
                        <password></password>
                        <context></context>
                    </auth>                    
                    <task>
                        <code>0202</code>
                    </task>
                </request>';
    }

} 
