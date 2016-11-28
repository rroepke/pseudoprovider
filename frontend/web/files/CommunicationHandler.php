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
    protected $ivsize;
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
        $this->ivsize = openssl_cipher_iv_length($chiffre);
    }

    /**
     * Validates response params
     *
     * @param stdClass $params
     * @param string $code
     */
    public function validate_response_params($params, $code = null) {
        $properties = ['code', 'timestamp'];
        if (is_null($code) || $code == self::CODE_SUCCESS) {
            $properties[] = 'pseudonym';
        }
        $this->validate_params($params, $properties);
    }

    /**
     * Validates request params
     *
     * @param stdClass $params
     */
    public function validate_request_params($params) {
        $properties = ['timestamp', 'service'];
        $this->validate_params($params, $properties);
    }

    /**
     * Validates params object wrt given properties
     *
     * @param stdClass $params
     * @param array $properties
     */
    protected function validate_params($params, $properties) {
        if (!($params instanceof \stdClass)) {
            $this->throw_exception(new \Exception("Params is not correctly formatted"));
        }

        foreach ($properties as $property) {
            if (!property_exists($params, $property)) {
                $this->throw_exception(new \Exception("Param " . $property . " is missing."));
            }

            if ($property == 'timestamp' && time() - 60 * 10 > $params->$property) {
                $this->throw_exception(new \Exception("Request outdated"));
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
        function ends_with($str, $sub) {
            return (substr($str, strlen($str) - strlen($sub)) === $sub);
        }

        if (is_null($timestamp)) {
            $timestamp = time();
        }

        $params = new \stdClass();
        $params->service = $service;
        $params->timestamp = $timestamp;

        $ciphertext = $this->encrypt_data($params);

        $mac = $this->compute_hmac($params);

        $params = new \stdClass();
        $params->service = $service;
        $params->cipher = $ciphertext;
        $params->mac = $mac;

        $query = http_build_query($params);

        if (strpos($url, '?') > 0 && ends_with($url, '?')) {
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

        $cipher = $this->encrypt_data($array);

        $mac = $this->compute_hmac($array);

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
    protected function encrypt_data($data) {
        $datastring = $this->encode_data($data);
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
            $ivsize = $this->ivsize;

            $iv = openssl_random_pseudo_bytes($ivsize);

            $ciphertext = openssl_encrypt($plaintext, $chiffre, $key, OPENSSL_RAW_DATA, $iv);

            $ciphertext = $iv . $ciphertext;

            $ciphertextbase64 = base64_encode($ciphertext);

            return $ciphertextbase64;

        } catch (\Exception $e) {
            $this->throw_exception(new \Exception("Error while encrypting data"));
        }

    }

    /**
     * Decrypts params object
     *
     * @param $ciphertext
     * @return stdClass
     */
    public function decrypt_data($ciphertext) {
        $datastring = $this->decrypt($ciphertext);

        return $this->decode_data($datastring);
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
            $ivsize = $this->ivsize;

            $ciphertextdec = base64_decode($ciphertext);

            $ivdec = substr($ciphertextdec, 0, $ivsize);

            $ciphertextdec = substr($ciphertextdec, $ivsize);

            $plaintextdec = openssl_decrypt($ciphertextdec, $chiffre, $key, OPENSSL_RAW_DATA, $ivdec);

            $plaintext = rtrim($plaintextdec, "\0");

            return $plaintext;
        } catch (\Exception $e) {
            $this->throw_exception(new \Exception("Error while decrypting data"));
        }
    }

    /**
     * Throws exception
     *
     * @param \Exception $e
     * @throws \Exception
     */
    protected function throw_exception($e) {
        throw new \Exception($e->getMessage());
    }

    /**
     * Encodes params object to string
     *
     * @param stdClass $data
     * @return string
     */
    protected function encode_data($data) {
        return json_encode($data);
    }

    /**
     * Decodes datastring to params object
     * @param string $datastring
     * @return stdClass
     */
    protected function decode_data($datastring) {
        return json_decode($datastring);
    }

    /**
     * Computes hmac of params object
     *
     * @param stdClass $data
     * @return string
     */
    protected function compute_hmac($data) {
        $hash = $this->hash;
        $key = $this->key;

        $datastring = $this->encode_data($data);

        return base64_encode(hash_hmac($hash, $datastring, $key));
    }

    /**
     * Verifies hmac of params object
     *
     * @param string $receivedhmac
     * @param stdClass $data
     */
    public function verify_hmac($receivedhmac, $data) {
        $hmac = $this->compute_hmac($data);

        if (!($hmac === $receivedhmac)) {
            $this->throw_exception(new \Exception('MAC invalid'));
        }
    }

}