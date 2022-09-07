<?php
namespace davidxu\base\helpers;

use davidxu\config\helpers\Html;
use Exception;
use Ramsey\Uuid\Uuid;
use yii\base\InvalidArgumentException;
use yii\helpers\StringHelper as YiiStringHelper;
use Yii;

/**
 * Class StringHelper
 * @package davidxu\base\helpers
 */
class StringHelper extends YiiStringHelper
{
    /**
     * Generate a random string
     *
     * @param int $length
     * @param bool $numeric
     * @return string
     * @throws Exception
     */
    public static function random(int $length, bool $numeric = false): string
    {
        $seed = base_convert(md5(microtime() . $_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
        $seed = $numeric ? (str_replace('0', '', $seed) . '012340567890') : ($seed . 'zZ' . strtoupper($seed));

        $hash = '';
        if (!$numeric) {
            $hash = chr(random_int(1, 26) + random_int(0, 1) * 32 + 64);
            $length--;
        }

        $max = strlen($seed) - 1;
        $seed = str_split($seed);
        for ($i = 0; $i < $length; $i++) {
            $hash .= $seed[random_int(0, $max)];
        }

        return $hash;
    }

    /**
     * Generate a number random string
     *
     * @param bool $prefix 判断是否需求前缀
     * @param int $length 长度
     * @return string
     */
    public static function randomNum(bool $prefix = false, int $length = 8): string
    {
        $str = $prefix ?? '';
        return $str . substr(implode(null, array_map('ord', str_split(substr(uniqid('', true), 7, 13)))), 0, $length);
    }
    
    /**
     * @param string $type
     * @param string $name
     * @return string
     * @throws Exception
     */
    protected static function uuid($type = '', $name = 'php.net'): string
    {
        switch ($type) {
            // represents a version 1 UUID
            case 'time' :
                $uuid = Uuid::uuid1();
                break;
            // Returns a version 3 (name-based) UUID based on the MD5 hash of a namespace ID and a name
            case 'md5' :
                $uuid = Uuid::uuid3(Uuid::NAMESPACE_DNS, $name);
                break;
            // Returns a version 4 (random) UUID
            case 'random' :
                $uuid = Uuid::uuid4();

                break;
            // Returns a version 5 (name-based) UUID based on the SHA-1 hash of a namespace ID and a name
            case 'sha1' :
                $uuid = Uuid::uuid5(Uuid::NAMESPACE_DNS, $name);
                break;
            // php uuid
            default :
                $uuid = md5(uniqid(md5(microtime(true) . random_bytes(8)), true));
        }
        return is_string($uuid) ? $uuid : $uuid->toString();
    }

    /**
     * Parase Enum attributes
     *
     * Input format a:des1,b:des2
     *
     * @param $string
     * @return array
     */
    public static function parseEnumAttr($string)
    {
        $array = preg_split('/[,;\r\n]+/', trim($string, ",;\r\n"));
        if (strpos($string, ':')) {
            $value = [];
            foreach ($array as $val) {
                list($k, $v) = explode(':', $val);
                $value[$k] = $v;
            }
        } else {
            $value = $array;
        }

        return $value;
    }

    /**
     * Generates a random string of specified length.
     * The string generated matches [A-Za-z0-9_]+ and is transparent to URL-encoding.
     *
     * @param int $length the length of the key in characters
     * @return string the generated random key
     * @throws \yii\base\Exception on failure.
     */
    public static function generateRandomString($length = 32)
    {
        if (!is_int($length)) {
            throw new InvalidArgumentException('First parameter ($length) must be an integer');
        }

        if ($length < 1) {
            throw new InvalidArgumentException('First parameter ($length) must be greater than 0');
        }

        $bytes = Yii::$app->security->generateRandomKey($length);
        return strtolower(substr(strtr(base64_encode($bytes), '+/', '__'), 0, $length));
    }

    public static function getInputId($name)
    {
        return Html::getInputIdByName($name);
    }

}
