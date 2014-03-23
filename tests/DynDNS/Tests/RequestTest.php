<?php

/*
* This file is part of the DynDNS library.
*
* (c) Andreas Weber <dialog@andreas-weber.me>
*
* This source file is subject to the MIT license that is bundled
* with this source code in the file LICENSE.
*/

namespace DynDNS\Tests;

use DynDNS\Updater\Request;


class RequestTest
    extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \BadMethodCallException
     */
    public function testIfExceptionIsThrownWhenNoIpIsGiven()
    {
        $request = new Request('foo.bar', null, null);
    }


    public function testIfInstantiatingWorksWhenIPv4IsGiven()
    {
        $request = new Request('foo.bar', '123.123.123.123', null);
        $this->assertInstanceOf('DynDNS\Updater\Request', $request);
    }


    public function testIfInstantiatingWorksWhenIPv6IsGiven()
    {
        $request = new Request('foo.bar', null, '2001:4860:4860::8888');
        $this->assertInstanceOf('DynDNS\Updater\Request', $request);
    }


    public function testIfInstantiatingWorksWhenBothIPsAreGiven()
    {
        $request = new Request('foo.bar', '123.123.123.123', '2001:4860:4860::8888');
        $this->assertInstanceOf('DynDNS\Updater\Request', $request);
    }


    public function testIfHostGetterWorks()
    {
        $host = 'foo.bar';
        $request = new Request($host, '123.123.123.123');
        $this->assertEquals($host, $request->getHost());
    }


    public function testIfIPv4GetterWorks()
    {
        $ipv4 = '123.123.123.123';
        $request = new Request('foo.bar', $ipv4);
        $this->assertEquals($ipv4, $request->getIPv4());
    }


    public function testIfIPv6GetterWorks()
    {
        $ipv6 = '2001:4860:4860::8888';
        $request = new Request('foo.bar', null, $ipv6);
        $this->assertEquals($ipv6, $request->getIPv6());
    }


    /**
     * @expectedException \InvalidArgumentException
     */
    public function testIfExceptionIsThrownWhenBadHostIsGiven()
    {
        $request = new Request('foo bar', '123.123.123.123');
    }


    /**
     * @expectedException \InvalidArgumentException
     */
    public function testIfExceptionIsThrownWhenBadIpv4IsGiven()
    {
        $request = new Request('foo.bar', 'bad ip');
    }


    /**
     * @expectedException \InvalidArgumentException
     */
    public function testIfExceptionIsThrownWhenBadIpv6IsGiven()
    {
        $request = new Request('foo.bar', null, 'bad ip');
    }
} 
