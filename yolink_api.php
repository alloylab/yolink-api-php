<?php

class yolink
{
    private $yolink_api;
    private $CSID;
    private $SecKey;

    public function __construct($CSID, $SecKey)
    {
        $this->yolink_api = 'https://api.yosmart.com/openApi';
        $this->user_agent = 'yolink';

        $this->CSID = $CSID;
        $this->SecKey = $SecKey;
    }

    private function oauth()
    {
        $this->CSID = $CSID;
        $this->SecKey = $SecKey;
        //Confirmed with Developer that Device Tokens Don't Expire... So I see no reason to automate OAUTH 2.0 Currently

        //http://yosmart.gitee.io/lorahomeapi-doc/#/YLAS/

        //OAuth url
        $url = 'https://api.yosmart.com/oauth/v2/authorization.htm?redirect_uri=/oauth/v2/getAccessToken.api&client_id=' . $CSID;
        // Then Login with Username/password and accessToken is returned as JSON
    }

    public function LeakSensor_getState($targetDevice, $token)
    {
        $post_json = json_encode(array(
            'method' => 'LeakSensor.getState',
            'time' => $this->time_milliseconds(),
            'targetDevice' => $targetDevice,
            'token' => $token
        ));

        $header = $this->auth_header($post_json);
        $sensor_data = json_decode($this->curl_header_post($header, $post_json));

        return $sensor_data;
    }

    public function online_check($sensor_state)
    {
        return $sensor_state->data->online;
    }

    public function leak_status($sensor_state)
    {
        return $sensor_state->data->state->state;
    }

    public function battery_check($unit_name, $af_unit_id, $sensor_state)
    {
        return $sensor_state->data->state->battery;
    }

    public function firmware_check($sensor_state)
    {
        return $sensor_state->data->state->version;
    }

    public function temperature_check($unit_name, $af_unit_id, $sensor_state)
    {
        //Note: I would apply a 9 degree offset for the acutal ambient temperature

        return $sensor_state->data->state->devTemperature;
    }

    private function auth_header($post_json)
    {
        $auth_header = array(
            'Accept: application/json',
            'Content-Type: application/json',
            'ktt-ys-brand: yolink',
            'KTT-CSID: ' . $this->CSID,
            'ys-sec:' . $this->hashed_secKey($post_json)
        );

        return $auth_header;
    }

    private function hashed_secKey($post_json)
    {
        $hash = strtolower(md5($post_json . $this->SecKey));

        return $hash;
    }

    private function time_milliseconds()
    {
        $milliseconds = round(microtime(true) * 1000);

        return $milliseconds;
    }

    private function curl_header_post($header, $post_data)
    {
        try {
            $ch = curl_init($this->yolink_api); // initialize curl with given url
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // write the response to a variable
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // follow redirects if any
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); // max. seconds to execute
            curl_setopt($ch, CURLOPT_FAILONERROR, true); // stop when it encounters an error
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            $result = curl_exec($ch);

            // Check the return value of curl_exec(), too
            if ($result === false) {
                throw new Exception(curl_error($ch), curl_errno($ch));
            }

            curl_close($ch);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        return $result;
    }
}
