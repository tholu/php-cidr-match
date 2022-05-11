<?php

use CIDRmatch\CIDRmatch;

/**
 * A suite of tests for the CIDRmatch class
 *
 * @author Thomas Lutz
 */
class CIDRmatchTest extends PHPUnit_Framework_TestCase
{
    /**
     * Ensure that IPv4 addresses match
     */
    public function testIPv4Match()
    {
        $cidrMatch = new CIDRmatch();
        $this->assertTrue($cidrMatch->match('104.132.31.99', '104.132.0.0/14'));
        $this->assertTrue($cidrMatch->match('74.125.60.99', '74.125.0.0/16'));

    }

    /**
     * Ensure that IPv4 addresses don't match with invalid input
     */
    public function testIPv4NoMatch()
    {
        $cidrMatch = new CIDRmatch();
        $this->assertFalse($cidrMatch->match('Not an IP address', '104.132.0.0/14'));
        $this->assertFalse($cidrMatch->match('104.132.31.99', 'Not an IP address/14'));
        $this->assertFalse($cidrMatch->match('104.132.31.99', '104.132.0.0/33'));
        $this->assertFalse($cidrMatch->match('104.132.31.99', '104.132.0.0/'));
        $this->assertFalse($cidrMatch->match('104.132.31.99', '104.132.0.0'));
    }

    /**
     * Ensure that IPv6 addresses match
     */
    public function testIPv6Match()
    {
        $cidrMatch = new CIDRmatch();
        $this->assertTrue($cidrMatch->match('2001:0db8:85a3:08d3:1319:8a2e:0370:7347', '2001:0db8:85a3:08d3::/64'));
        $this->assertTrue($cidrMatch->match('2a00:1450:400c:c04::6a', '2a00:1450::/32'));
        $this->assertTrue($cidrMatch->match('2001:0db8:85a3:08d3:1319:8a2e:0370:7347', '2001:0db8:85a3:08d3:1319:8a2e:0370:7347'));
    }

    /**
     * Ensure that IPv6 addresses don't match with invalid input
     */
    public function testIPv6NoMatch()
    {
        $cidrMatch = new CIDRmatch();
        $this->assertFalse($cidrMatch->match('Not an IP address', '2001:0db8:85a3:08d3::/64'));
        $this->assertFalse($cidrMatch->match('2001:0db8:85a3:08d3:1319:8a2e:0370:7347', 'Not an IP address/64'));
        $this->assertFalse($cidrMatch->match('2001:0db8:85a3:08d3:1319:8a2e:0370:7347', '2001:0db8:85a3:08d3::/129'));
        $this->assertFalse($cidrMatch->match('2001:0db8:85a3:08d3:1319:8a2e:0370:7347', '2001:0db8:85a3:08d3::/'));
        $this->assertFalse($cidrMatch->match('2001:0db8:85a3:08d3:1319:8a2e:0370:7347', '2001:0db8:85a3:08d3::'));
    }

    /**
     * Ensure that case from issue #2 (https://github.com/tholu/php-cidr-match/issues/2) works
     */
    public function testIssue2() {
        $cidrMatch = new CIDRmatch();

        $addr = '192.168.1.1';
        $this->assertTrue($cidrMatch->match($addr, '192.168.1.0/24'));
        $this->assertFalse($cidrMatch->match($addr, '1.2.3.4/1'));
        $this->assertFalse($cidrMatch->match($addr, '192.168.1.1/33')); // invalid subnet
        $this->assertTrue($cidrMatch->match($addr,  '0.0.0.0/0'));
        $this->assertFalse($cidrMatch->match($addr, '256.256.256/0')); // invalid CIDR notation

        $addr = '10.5.5.1';
        $this->assertTrue($cidrMatch->match($addr, '10.0.0.1/8'));
        $this->assertTrue($cidrMatch->match($addr, '10.0.0.10/8'));
        $this->assertTrue($cidrMatch->match($addr, '10.5.5.0/16'));
        $this->assertFalse($cidrMatch->match($addr, '10.4.5.0/16'));
        $this->assertTrue($cidrMatch->match($addr, '10.4.5.0/15'));

        $this->assertTrue($cidrMatch->match('2a01:198:603:0:396e:4789:8e99:890f', '2a01:198:603:0::/65'));
        $this->assertFalse($cidrMatch->match('2a00:198:603:0:396e:4789:8e99:890f', '2a01:198:603:0::/65'));
        $this->assertFalse($cidrMatch->match('2a01:198:603:0:396e:4789:8e99:890f', '::1'));
        $this->assertFalse($cidrMatch->match('0:0:603:0:396e:4789:8e99:0001', '::1'));
        $this->assertFalse($cidrMatch->match('}__test|O:21:&quot;JDatabaseDriverMysqli&quot;:3:{s:2', '::1'));   
    }

    /**
     * Ensure that case from issue #3 (https://github.com/tholu/php-cidr-match/issues/3) works
     */
    public function testIssue3() {
        $cidrMatch = new CIDRmatch();
        $this->assertFalse($cidrMatch->match('91.124.36.116', '2a02:6b8:0:1a00::/56'));
    }

}
