<?php

namespace App\Utilities;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\Output\QRGdImagePNG;

class PayNow
{
    /**
     * Calculate 16-bit CRC (CCITT-FALSE: poly 0x1021, init 0xFFFF).
     */
    public static function crc16Ccitt(string $str): string
    {
        $crc = 0xFFFF;
        $len = strlen($str);
        for ($c = 0; $c < $len; $c++) {
            $crc ^= (ord($str[$c]) << 8);
            for ($i = 0; $i < 8; $i++) {
                if ($crc & 0x8000) {
                    $crc = (($crc << 1) ^ 0x1021) & 0xFFFF;
                } else {
                    $crc = ($crc << 1) & 0xFFFF;
                }
            }
        }
        return strtoupper(str_pad(dechex($crc), 4, '0', STR_PAD_LEFT));
    }

    /**
     * Formats an EMV Tag-Length-Value chunk.
     */
    public static function emvTag(string $tag, string $value): string
    {
        $len = str_pad((string) strlen($value), 2, '0', STR_PAD_LEFT);
        return $tag . $len . $value;
    }

    /**
     * Generate Singapore EMVCo PayNow SGQR payload.
     *
     * @param string $proxy UEN (e.g. 200415432K) or Mobile (e.g. +6591234567)
     * @param float|int|string|null $amount Invoice amount (e.g. 150.00)
     * @param string $ref Reference / Invoice Number (e.g. INV-0001)
     * @param string $companyName Merchant / Company Name
     * @param string $proxyType '2' for UEN (default), '0' for Mobile
     * @return string Complete EMVCo QR String
     */
    public static function generatePayload(
        string $proxy,
        $amount = null,
        string $ref = '',
        string $companyName = 'Merchant',
        string $proxyType = '2'
    ): string {
        $cleanProxy = trim(strtoupper($proxy));
        $hasAmount = !empty($amount) && (float) $amount > 0;
        $isMobile = ($proxyType === '0' || str_starts_with($cleanProxy, '+') || (strlen($cleanProxy) === 8 && is_numeric($cleanProxy)));
        $typeTag = $isMobile ? '0' : '2';

        if ($isMobile && !str_starts_with($cleanProxy, '+') && strlen($cleanProxy) === 8) {
            $cleanProxy = '+65' . $cleanProxy;
        }

        // Subtags for Tag 26 (Merchant Account Information - PayNow)
        $sub26 = self::emvTag('00', 'SG.PAYNOW');
        $sub26 .= self::emvTag('01', $typeTag);
        $sub26 .= self::emvTag('02', $cleanProxy);
        $sub26 .= self::emvTag('03', $hasAmount ? '0' : '1'); // '0' = exact amount, '1' = editable

        // Root EMVCo Payload
        $payload = self::emvTag('00', '01'); // Format Indicator
        $payload .= self::emvTag('01', $hasAmount ? '12' : '11'); // 12 = Dynamic QR, 11 = Static QR
        $payload .= self::emvTag('26', $sub26);
        $payload .= self::emvTag('52', '0000'); // Merchant Category Code
        $payload .= self::emvTag('53', '702'); // ISO 4217 Currency: SGD (702)

        if ($hasAmount) {
            $formattedAmount = number_format((float) $amount, 2, '.', '');
            $payload .= self::emvTag('54', $formattedAmount);
        }

        $payload .= self::emvTag('58', 'SG'); // Country code
        $merchantName = !empty(trim($companyName)) ? trim($companyName) : 'Merchant';
        $payload .= self::emvTag('59', substr($merchantName, 0, 25)); // Merchant Name
        $payload .= self::emvTag('60', 'Singapore'); // Merchant City

        if (!empty($ref)) {
            $cleanRef = substr(preg_replace('/[^A-Za-z0-9\-_]/', '', $ref), 0, 25);
            $sub62 = self::emvTag('01', $cleanRef);
            $payload .= self::emvTag('62', $sub62);
        }

        $payload .= '6304';
        $checksum = self::crc16Ccitt($payload);

        return $payload . $checksum;
    }

    /**
     * Generate a PNG Data URI for embedding in <img> tags.
     * Compatible with both web browsers and DomPDF rendering engines.
     */
    public static function generateQrCodeDataUri(
        string $proxy,
        $amount = null,
        string $ref = '',
        string $companyName = 'Merchant',
        string $proxyType = '2'
    ): string {
        try {
            $payload = self::generatePayload($proxy, $amount, $ref, $companyName, $proxyType);

            $options = new QROptions([
                'outputInterface'  => QRGdImagePNG::class,
                'outputBase64'     => true,
                'scale'            => 6,
                'imageTransparent' => false,
                'quietzoneSize'    => 2,
            ]);

            return (new QRCode($options))->render($payload);
        } catch (\Throwable $e) {
            // Fallback to default render if options fail
            $payload = self::generatePayload($proxy, $amount, $ref, $companyName, $proxyType);
            return (new QRCode)->render($payload);
        }
    }
}
