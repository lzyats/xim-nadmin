<?php
namespace app\common\library;

class AesCrypt
{
    private $key;
    private $iv;

    public function __construct($key, $iv)
    {
        $this->key = $key;
        $this->iv = $iv;
    }

    /**
     * AES - 256 - CBC 加密
     *
     * @param string $data 要加密的数据
     * @return string 加密后的 Base64 编码字符串
     */
    public function encrypt($data)
    {
        $encrypted = openssl_encrypt(
            $data,
            'AES-256-CBC',
            $this->key,
            OPENSSL_RAW_DATA,
            $this->iv
        );
        return base64_encode($encrypted);
    }

    /**
     * AES - 256 - CBC 解密
     *
     * @param string $encryptedData 加密后的 Base64 编码字符串
     * @return string 解密后的数据
     */
    public function decrypt($encryptedData)
    {
        $decoded = base64_decode($encryptedData);
        $decrypted = openssl_decrypt(
            $decoded,
            'AES-256-CBC',
            $this->key,
            OPENSSL_RAW_DATA,
            $this->iv
        );
        if ($decrypted === false) {
            throw new \Exception('解密失败：数据无效或密钥错误');
        }
        return $decrypted;
    }
}