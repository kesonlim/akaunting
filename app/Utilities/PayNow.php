<?php

namespace App\Utilities;

use chillerlan\QRCode\QRCode;

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
     * @param string $uen Company UEN (e.g. 202412345K)
     * @param float|int|string|null $amount Invoice amount (e.g. 150.00)
     * @param string $ref Reference / Invoice Number (e.g. INV-0001)
     * @param string $companyName Merchant / Company Name
     * @return string Complete EMVCo QR String
     */
    public static function generatePayload(
        string $uen,
        $amount = null,
        string $ref = '',
        string $companyName = 'Straits Leisure'
    ): string {
        $cleanUen = trim(strtoupper($uen));
        $hasAmount = !empty($amount) && (float) $amount > 0;

        // Subtags for Tag 26 (Merchant Account Information - PayNow)
        $sub26 = self::emvTag('00', 'SG.PAYNOW');
        $sub26 .= self::emvTag('01', '2'); // '2' represents UEN in Singapore PayNow spec ('0' is mobile)
        $sub26 .= self::emvTag('02', $cleanUen);
        $sub26 .= self::emvTag('03', $hasAmount ? '0' : '1'); // '0' = exact/locked amount, '1' = editable amount

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
        $payload .= self::emvTag('59', substr(trim($companyName), 0, 25)); // Merchant Name
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
     * Generate an SVG Data URI for embedding in <img> tags.
     */
    public static function generateQrCodeDataUri(
        string $uen,
        $amount = null,
        string $ref = '',
        string $companyName = 'Straits Leisure'
    ): string {
        $payload = self::generatePayload($uen, $amount, $ref, $companyName);
        return (new QRCode)->render($payload);
    }
}
