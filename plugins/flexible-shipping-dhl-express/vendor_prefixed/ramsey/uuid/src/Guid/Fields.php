<?php

/**
 * This file is part of the ramsey/uuid library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) Ben Ramsey <ben@benramsey.com>
 * @license http://opensource.org/licenses/MIT MIT
 */
declare (strict_types=1);
namespace DhlVendor\Ramsey\Uuid\Guid;

use DhlVendor\Ramsey\Uuid\Exception\InvalidArgumentException;
use DhlVendor\Ramsey\Uuid\Fields\SerializableFieldsTrait;
use DhlVendor\Ramsey\Uuid\Rfc4122\FieldsInterface;
use DhlVendor\Ramsey\Uuid\Rfc4122\NilTrait;
use DhlVendor\Ramsey\Uuid\Rfc4122\VariantTrait;
use DhlVendor\Ramsey\Uuid\Rfc4122\VersionTrait;
use DhlVendor\Ramsey\Uuid\Type\Hexadecimal;
use DhlVendor\Ramsey\Uuid\Uuid;
use function bin2hex;
use function dechex;
use function hexdec;
use function pack;
use function sprintf;
use function str_pad;
use function strlen;
use function substr;
use function unpack;
use const STR_PAD_LEFT;
/**
 * GUIDs are comprised of a set of named fields, according to RFC 4122
 *
 * @see Guid
 *
 * @psalm-immutable
 */
final class Fields implements \DhlVendor\Ramsey\Uuid\Rfc4122\FieldsInterface
{
    use NilTrait;
    use SerializableFieldsTrait;
    use VariantTrait;
    use VersionTrait;
    /**
     * @var string
     */
    private $bytes;
    /**
     * @param string $bytes A 16-byte binary string representation of a UUID
     *
     * @throws InvalidArgumentException if the byte string is not exactly 16 bytes
     * @throws InvalidArgumentException if the byte string does not represent a GUID
     * @throws InvalidArgumentException if the byte string does not contain a valid version
     */
    public function __construct(string $bytes)
    {
        if (\strlen($bytes) !== 16) {
            throw new \DhlVendor\Ramsey\Uuid\Exception\InvalidArgumentException('The byte string must be 16 bytes long; ' . 'received ' . \strlen($bytes) . ' bytes');
        }
        $this->bytes = $bytes;
        if (!$this->isCorrectVariant()) {
            throw new \DhlVendor\Ramsey\Uuid\Exception\InvalidArgumentException('The byte string received does not conform to the RFC ' . '4122 or Microsoft Corporation variants');
        }
        if (!$this->isCorrectVersion()) {
            throw new \DhlVendor\Ramsey\Uuid\Exception\InvalidArgumentException('The byte string received does not contain a valid version');
        }
    }
    public function getBytes() : string
    {
        return $this->bytes;
    }
    public function getTimeLow() : \DhlVendor\Ramsey\Uuid\Type\Hexadecimal
    {
        // Swap the bytes from little endian to network byte order.
        /** @var array $hex */
        $hex = \unpack('H*', \pack('v*', \hexdec(\bin2hex(\substr($this->bytes, 2, 2))), \hexdec(\bin2hex(\substr($this->bytes, 0, 2)))));
        return new \DhlVendor\Ramsey\Uuid\Type\Hexadecimal((string) ($hex[1] ?? ''));
    }
    public function getTimeMid() : \DhlVendor\Ramsey\Uuid\Type\Hexadecimal
    {
        // Swap the bytes from little endian to network byte order.
        /** @var array $hex */
        $hex = \unpack('H*', \pack('v', \hexdec(\bin2hex(\substr($this->bytes, 4, 2)))));
        return new \DhlVendor\Ramsey\Uuid\Type\Hexadecimal((string) ($hex[1] ?? ''));
    }
    public function getTimeHiAndVersion() : \DhlVendor\Ramsey\Uuid\Type\Hexadecimal
    {
        // Swap the bytes from little endian to network byte order.
        /** @var array $hex */
        $hex = \unpack('H*', \pack('v', \hexdec(\bin2hex(\substr($this->bytes, 6, 2)))));
        return new \DhlVendor\Ramsey\Uuid\Type\Hexadecimal((string) ($hex[1] ?? ''));
    }
    public function getTimestamp() : \DhlVendor\Ramsey\Uuid\Type\Hexadecimal
    {
        return new \DhlVendor\Ramsey\Uuid\Type\Hexadecimal(\sprintf('%03x%04s%08s', \hexdec($this->getTimeHiAndVersion()->toString()) & 0xfff, $this->getTimeMid()->toString(), $this->getTimeLow()->toString()));
    }
    public function getClockSeq() : \DhlVendor\Ramsey\Uuid\Type\Hexadecimal
    {
        $clockSeq = \hexdec(\bin2hex(\substr($this->bytes, 8, 2))) & 0x3fff;
        return new \DhlVendor\Ramsey\Uuid\Type\Hexadecimal(\str_pad(\dechex($clockSeq), 4, '0', \STR_PAD_LEFT));
    }
    public function getClockSeqHiAndReserved() : \DhlVendor\Ramsey\Uuid\Type\Hexadecimal
    {
        return new \DhlVendor\Ramsey\Uuid\Type\Hexadecimal(\bin2hex(\substr($this->bytes, 8, 1)));
    }
    public function getClockSeqLow() : \DhlVendor\Ramsey\Uuid\Type\Hexadecimal
    {
        return new \DhlVendor\Ramsey\Uuid\Type\Hexadecimal(\bin2hex(\substr($this->bytes, 9, 1)));
    }
    public function getNode() : \DhlVendor\Ramsey\Uuid\Type\Hexadecimal
    {
        return new \DhlVendor\Ramsey\Uuid\Type\Hexadecimal(\bin2hex(\substr($this->bytes, 10)));
    }
    public function getVersion() : ?int
    {
        if ($this->isNil()) {
            return null;
        }
        /** @var array $parts */
        $parts = \unpack('n*', $this->bytes);
        return (int) $parts[4] >> 4 & 0xf;
    }
    private function isCorrectVariant() : bool
    {
        if ($this->isNil()) {
            return \true;
        }
        $variant = $this->getVariant();
        return $variant === \DhlVendor\Ramsey\Uuid\Uuid::RFC_4122 || $variant === \DhlVendor\Ramsey\Uuid\Uuid::RESERVED_MICROSOFT;
    }
}
