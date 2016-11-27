<?php
/**
 * Class CommunicationHandler
 *
 * Please inherit from this class and override method 'throwException'
 *
 * @author Rene Roepke
 */
class CommunicationHandler {

    const CODE_SUCCESS = 'success';
    const CODE_FAIL = 'fail';

    /** @var string key material */
    protected $key;
    /** @var string openssl chiffre */
    protected $chiffre;
    /** @var int size of initialization vector */
    protected $iv_size;
    /** @var string hash function for hmac */
    protected $hash;

    /**
     * CommunicationHandler constructor.
     * @param string $key
     * @param string $chiffre
     * @param string $hash
     */
    public function __construct($key, $chiffre = 'AES-256-CBC', $hash = 'sha256') {

        $this->key = pack('H*', $key);
        $this->chiffre = $chiffre;
        $this->hash = $hash;
        $this->iv_size = openssl_cipher_iv_length($chiffre);
    }

    /**
     * Validates response params
     *
     * @param stdClass $params
     * @param string $code
     */
    public function validateResponseParams($params, $code = null) {
        $properties = ['code','timestamp'];
        if (is_null($code) || $code == self::CODE_SUCCESS) {
            $properties[] = 'pseudonym';
        }
        $this->validateParams($params, $properties);
    }

    /**
     * Validates request params
     *
     * @param stdClass $params
     */
    public function validateRequestParams($params) {
        $properties = ['timestamp', 'service'];
        $this->validateParams($params, $properties);
    }

    /**
     * Validates params object wrt given properties
     *
     * @param stdClass $params
     * @param array $properties
     */
    protected function validateParams($params, $properties) {
        if (!($params instanceof \stdClass)) {
            $this->throwException(new \Exception("Params is not correctly formatted"));
        }

        foreach ($properties as $property) {
            if (!property_exists($params, $property)) {
                $this->throwException(new \Exception("Param " . $property . " is missing."));
            }

            if ($property == 'timestamp' && time() - 60 * 10 > $params->$property) {
                $this->throwException(new \Exception("Request outdated"));
            }
        }
    }

    /**
     * Builds request url
     *
     * @param string $url
     * @param string $service
     * @param null $timestamp
     * @return string
     */
    public function build_request($url, $service, $timestamp = null) {
        function endsWith($str, $sub) {
            return (substr($str, strlen($str) - strlen($sub)) === $sub);
        }

        if (is_null($timestamp)) {
            $timestamp = time();
        }

        $params = new \stdClass();
        $params->service = $service;
        $params->timestamp = $timestamp;

        $ciphertext = $this->encryptData($params);

        $mac = $this->computeHMAC($params);

        $params = new \stdClass();
        $params->service = $service;
        $params->cipher = $ciphertext;
        $params->mac = $mac;

        $query = http_build_query($params);

        if (strpos($url, '?') > 0 && endsWith($url, '?')) {
            $request = $url . $query;
        } else if (strpos($url, '?') > 0) {
            $request = $url . '&' . $query;
        } else {
            $request = $url . '?' . $query;
        }

        return $request;
    }

    /**
     * Builds response url
     *
     * @param string $url
     * @param string $code
     * @param null $timestamp
     * @param string $pseudonym
     * @return string
     */
    public function build_response($url, $code, $timestamp = null, $pseudonym = null) {
        if (is_null($timestamp)) {
            $timestamp = time();
        }
        $array = new \stdClass();
        $array->code = $code;
        $array->timestamp = $timestamp;

        if (!is_null($pseudonym)) {
            $array->pseudonym = $pseudonym;
        }

        $cipher = $this->encryptData($array);

        $mac = $this->computeHMAC($array);

        $array = new \stdClass();
        $array->code = $code;
        $array->cipher = $cipher;
        $array->mac = $mac;

        $query = http_build_query($array);

        if (strpos($url, '?') > 0 && stringEndsWith('?')) {
            $response = $url . $query;
        } else if (strpos($url, '?') > 0) {
            $response = $url . '&' . $query;
        } else {
            $response = $url . '?' . $query;
        }

        return $response;
    }

    /**
     * Encrypts params object
     *
     * @param $data
     * @return string
     */
    protected function encryptData($data) {
        $datastring = $this->encodeData($data);
        return $this->encrypt($datastring);
    }

    /**
     * Encrypts plaintext
     *
     * @param $plaintext
     * @return string
     */
    protected function encrypt($plaintext) {
        try {
            $chiffre = $this->chiffre;
            $key = $this->key;
            $iv_size = $this->iv_size;

            $iv = openssl_random_pseudo_bytes($iv_size);

            $ciphertext = openssl_encrypt($plaintext, $chiffre, $key, OPENSSL_RAW_DATA, $iv);

            $ciphertext = $iv . $ciphertext;

            $ciphertext_base64 = base64_encode($ciphertext);

            return $ciphertext_base64;

        } catch (\Exception $e) {
            $this->throwException(new \Exception("Error while encrypting data"));
        }

    }

    /**
     * Decrypts params object
     *
     * @param $ciphertext
     * @return stdClass
     */
    public function decryptData($ciphertext) {
        $datastring = $this->decrypt($ciphertext);

        return $this->decodeData($datastring);
    }

    /**
     * Decrypts ciphertext
     *
     * @param $ciphertext
     * @return string
     */
    protected function decrypt($ciphertext) {
        try {
            $chiffre = $this->chiffre;
            $key = $this->key;
            $iv_size = $this->iv_size;

            $ciphertext_dec = base64_decode($ciphertext);

            $iv_dec = substr($ciphertext_dec, 0, $iv_size);

            $ciphertext_dec = substr($ciphertext_dec, $iv_size);

            $plaintext_dec = openssl_decrypt($ciphertext_dec, $chiffre, $key, OPENSSL_RAW_DATA, $iv_dec);

            $plaintext = rtrim($plaintext_dec, "\0");

            return $plaintext;
        } catch (\Exception $e) {
            $this->throwException(new \Exception("Error while decrypting data"));
        }
    }

    /**
     * Throws exception
     *
     * @param \Exception $e
     * @throws \Exception
     */
    protected function throwException($e) {
        throw new \Exception($e->getMessage());
    }

    /**
     * Encodes params object to string
     *
     * @param stdClass $data
     * @return string
     */
    protected function encodeData($data) {
        return json_encode($data);
    }

    /**
     * Decodes datastring to params object
     * @param string $datastring
     * @return stdClass
     */
    protected function decodeData($datastring) {
        return json_decode($datastring);
    }

    /**
     * Computes hmac of params object
     *
     * @param stdClass $data
     * @return string
     */
    protected function computeHMAC($data) {
        $hash = $this->hash;
        $key = $this->key;

        $datastring = $this->encodeData($data);

        return base64_encode(hash_hmac($hash, $datastring, $key));
    }

    /**
     * Verifies hmac of params object
     *
     * @param string $received_hmac
     * @param stdClass $data
     */
    public function verifyHMAC($received_hmac, $data) {
        $hmac = $this->computeHMAC($data);

        if (!($hmac === $received_hmac)) {
            $this->throwException(new \Exception('MAC invalid'));
        }
    }

}