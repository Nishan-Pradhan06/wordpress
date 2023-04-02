<?php

class Czar_Astra_Model_Encryption
{
    /**
     * Encrypt function
     *
     * @param string $plainText Text to encrypt
     * @param string $key       Key used to encrypt the text
     *
     * @return string
     */
    public function encrypt($plainText, $key)
    {
        $ivsize = openssl_cipher_iv_length('AES-128-CBC');
        $plainPad = $this->pkcs5Pad($plainText, $ivsize);
        $secretKey = $this->hextobin(md5($key));
        $initVector = pack(
            "C*",
            0x00,
            0x01,
            0x02,
            0x03,
            0x04,
            0x05,
            0x06,
            0x07,
            0x08,
            0x09,
            0x0a,
            0x0b,
            0x0c,
            0x0d,
            0x0e,
            0x0f
        );

        $ciphertext = openssl_encrypt(
            $plainPad,
            'AES-128-CBC',
            $secretKey,
            OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING,
            $initVector
        );

        return bin2hex($ciphertext);
    }
    
    /**
     * Decrypt function
     *
     * @param string $encryptedText Text to be decrypted
     * @param string $key           Key used to decrypt the text
     *
     * @return string
     */
    public function decrypt($encryptedText, $key)
    {
        $secretKey = $this->hextobin(md5($key));
        $initVector = pack(
            "C*",
            0x00,
            0x01,
            0x02,
            0x03,
            0x04,
            0x05,
            0x06,
            0x07,
            0x08,
            0x09,
            0x0a,
            0x0b,
            0x0c,
            0x0d,
            0x0e,
            0x0f
        );
        $encryptedText = $this->hextobin($encryptedText);

        $decryptedText = openssl_decrypt(
            $encryptedText,
            'AES-128-CBC',
            $secretKey,
            OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING,
            $initVector
        );

        $decryptedText = rtrim($decryptedText, "\0");
        return $decryptedText;
    }
    
    /**
     * Padding function
     *
     * @param string $plainText Text to be padded
     * @param string $blockSize Block size
     *
     * @return string
     */
    public function pkcs5Pad($plainText, $blockSize)
    {
        $pad = $blockSize - (strlen($plainText) % $blockSize);
        //@codingStandardsIgnoreStart
        return $plainText . str_repeat(chr($pad), $pad);
        //@codingStandardsIgnoreEnd
    }
    
    /**
     * Hexadecimal to Binary function
     *
     * @param string $hexString Hex string to be converted to binary
     *
     * @return string
     */
    public function hextobin($hexString)
    {
        $length = strlen($hexString);
        $binString = "";
        $count = 0;
        while ($count < $length) {
            $subString = substr($hexString, $count, 2);
            $packedString = pack("H*", $subString);
            if ($count == 0) {
                $binString = $packedString;
            } else {
                $binString .= $packedString;
            }

            $count += 2;
        }

        return $binString;
    }
}
