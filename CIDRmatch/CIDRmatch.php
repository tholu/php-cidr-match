<?php
namespace CIDRmatch;

/** CIDR match
 * ================================================================================
 * IDRmatch is a library to match an IP to an IP range in CIDR notation (IPv4 and
 * IPv6).
 *  ================================================================================
 * @package     CIDRmatch
 * @author      Thomas Lutz
 * @copyright   Copyright (c) 2015 - present Thomas Lutz
 * @license     http://tholu.mit-license.org
 *  ================================================================================
 */

class CIDRmatch
{

    public function match($ip, $cidr)
    {
        $c = explode('/', $cidr);
        $subnet = isset($c[0]) ? $c[0] : NULL;
        $mask   = isset($c[1]) ? $c[1] : NULL;

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            // it's valid
            $ipVersion = 'v4';
        } else {
            // it's not valid
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                // it's valid
                $ipVersion = 'v6';
            } else {
                // it's not valid
                return false;
            }
        }

        switch ($ipVersion) {
            case 'v4':
                if ($mask === null) {
                    $mask = 32;
                }

                return $this->IPv4Match($ip, $subnet, $mask);
                break;
            case 'v6':
                if ($mask === null) {
                    $mask = 128;
                }

                return $this->IPv6Match($ip, $subnet, $mask);
                break;
        }
    }

    // inspired by: http://stackoverflow.com/questions/7951061/matching-ipv6-address-to-a-cidr-subnet
    private function IPv6MaskToByteArray($subnetMask)
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
    private function IPv6Match($address, $subnetAddress, $subnetMask)
    {
        if (!filter_var($subnetAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) || $subnetMask === NULL || $subnetMask === "" || $subnetMask < 0 || $subnetMask > 128) {
            return false;
        }
        $subnet = inet_pton($subnetAddress);
        $addr = inet_pton($address);

        $binMask = $this->IPv6MaskToByteArray($subnetMask);

        return ($addr & $binMask) == $subnet;
    }

    // inspired by: http://stackoverflow.com/questions/594112/matching-an-ip-to-a-cidr-mask-in-php5
    private function IPv4Match($address, $subnetAddress, $subnetMask)
    {
        if (!filter_var($subnetAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) || $subnetMask === NULL || $subnetMask === "" || $subnetMask < 0 || $subnetMask > 32) {
            return false;
        }

        $address = ip2long($address);
        $subnetAddress = ip2long($subnetAddress);
        $mask = -1 << (32 - $subnetMask);
        $subnetAddress &= $mask; # nb: in case the supplied subnet wasn't correctly aligned
        return ($address & $mask) == $subnetAddress;
    }

}
