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

use DynDNS\Updater\Response;


class ResponseTest
    extends \PHPUnit_Framework_TestCase
{
    public function testIfSuccessGetterWorks()
    {
        $response = new Response(true);
        $this->assertTrue($response->getSuccess());

        $response = new Response(0);
        $this->assertFalse($response->getSuccess());
    }


    public function testIfRawResponseGetterWorks()
    {
        $raw = array(
            'some'  => 'cool',
            'stuff' => 'yeah'
        );

        $response = new Response(true, $raw);
        $this->assertEquals($raw, $response->getRawResponse());
    }
} 
