<?php
namespace CIDRmatch;

/** CIDR match
 * ================================================================================
 * PHP library to check if ip adress is included in ip range (CIDR notation)
 *  ================================================================================
 * @package     CIDRmatch
 * @author      Thomas Lutz
 * @copyright   Copyright (c) 2015 - present Thomas Lutz
 * @license     http://tholu.mit-license.org
 *  ================================================================================
 */

class CIDRmatch
{

    function match($ip, $range)
    {
        $ip_version = false;
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            // it's valid
            $ip_version = 'v4';
        } else {
            // it's not valid
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                // it's valid
                $ip_version = 'v6';
            } else {
                // it's not valid
                return false;
            }
        }
        if (!$ip_version) {
            return false;
        }

        switch ($ip_version) {
            case 'v4':

                break;
            case 'v6':
                $range_arr = explode('/', $range);
                $subnet = inet_pton($range_arr[0]);
                $mask = $range_arr[1];
                $addr = inet_pton($ip);

                return ipv6_cidr_match($addr, $subnet, $mask);
                break;
        }
    }

    // inspired by: http://stackoverflow.com/questions/7951061/matching-ipv6-address-to-a-cidr-subnet
    function ipv6_mask_to_byte_array($subnetMask)
    {
        $addr = str_repeat("f", $subnetMask / 4);
        switch ($subnetMask % 4) {
            case 0:
                break;
            case 1:
                $addr .= "8";
                break;
            case 2:
                $addr .= "c";
                break;
            case 3:
                $addr .= "e";
                break;
        }
        $addr = str_pad($addr, 32, '0');
        $addr = pack("H*", $addr);

        return $addr;
    }

    // inspired by: http://stackoverflow.com/questions/7951061/matching-ipv6-address-to-a-cidr-subnet
    function ipv6_cidr_match($address, $subnetAddress, $subnetMask)
    {
        $binMask = ipv6_mask_to_byte_array($subnetMask);

        return ($address & $binMask) == $subnetAddress;
    }

}
