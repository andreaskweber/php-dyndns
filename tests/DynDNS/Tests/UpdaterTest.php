<?php

/*
* This file is part of the DynDNS library.
*
* (c) Andreas Weber <weber@webmanufaktur-weber.de>
*
* This source file is subject to the MIT license that is bundled
* with this source code in the file LICENSE.
*/

namespace DynDNS\Tests;

use DynDNS\Updater;


class UpdaterTest
    extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \RuntimeException
     */
    public function testResponseGetterThrowsExceptionWhenNoUpdateWasPerformed()
    {
        $updater = new Updater(
            $this->getMock('\DynDNS\ProviderInterface')
        );

        $updater->getResponse();
    }


    public function testResponseGetterReturnsResponseInstanceWhenUpdateWasCalled()
    {
        $providerMock = $this->getMock('\DynDNS\ProviderInterface');
        $providerMock
            ->expects($this->once())
            ->method('update')
            ->will($this->returnValue(
                $this->getMock('\DynDNS\Updater\ResponseInterface')
            ));

        $updater = new Updater($providerMock);
        $updater->update(
            'foo.bar',
            '123.123.123.123',
            '2001:4860:4860::8888'
        );

        $response = $updater->getResponse();
        $this->assertInstanceOf('\DynDNS\Updater\ResponseInterface', $response);
    }


    public function testUpdateMethodReturnsTrue()
    {
        $responseMock = $this->getMock('\DynDNS\Updater\ResponseInterface');
        $responseMock->expects($this->once())
            ->method('getSuccess')
            ->will($this->returnValue(true));

        $providerMock = $this->getMock('\DynDNS\ProviderInterface');
        $providerMock
            ->expects($this->once())
            ->method('update')
            ->will($this->returnValue($responseMock));

        $updater = new Updater($providerMock);
        $successful = $updater->update(
            'foo.bar',
            '123.123.123.123',
            '2001:4860:4860::8888'
        );

        $this->assertTrue($successful);
    }
} 
