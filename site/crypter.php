<?php
declare(strict_types=1);
require_once('view.php');
require_once('fnc.php');

class Crypter
{
    private static string $algo = 'aes-256-cbc';
    private int $ivSize;
    public function __construct()
    {
        $this->ivSize = openssl_cipher_iv_length(self::$algo);
    }

    public function encode(mixed &$trg, string $key, string &$text)
    {
        $iv = openssl_random_pseudo_bytes($this->ivSize);
        $trg = $iv . openssl_encrypt($text, self::$algo, $key, 0, $iv);
    }

    function decode(mixed &$trg, string $key, string &$bin)
    {
        $trg = openssl_decrypt(substr($bin, $this->ivSize), self::$algo, $key, 0, substr($bin, 0, $this->ivSize));
    }
//  TODO password hash and check
// $pwd = 'Test123';
// var_dump($pwd);
// $hash = password_hash($pwd, PASSWORD_DEFAULT);
// var_dump($hash);

// $ok = hash_equals($hash, crypt($pwd, $hash));
// var_dump($ok);

}

function crypter()
{
    static $instance = new Crypter();
    return $instance;
}
?>
