<?php

namespace YoLink;

class LeakSensor
{
    public static function getState($authPaylod, $targetDevice, $targetToken)
    {
        $post_json = json_encode(array(
            'method' => 'LeakSensor.getState',
            'time' => \YoLink\Helper::time_milliseconds(),
            'targetDevice' => $targetDevice,
            'token' => $targetToken
        ));

        $url = $authPaylod->yolink_api;
        $auth_header = $authPaylod->header;
        $auth_header['headers']['ys-sec'] = \YoLink\Helper::hashed_secKey($post_json, $authPaylod->SecKey);

        $request = \YoLink\Client::post($url, $auth_header, $post_json);

        $sensor_data = json_decode($request);

        return $sensor_data;
    }

    public static function online_check($sensor_state)
    {
        return $sensor_state->data->online;
    }

    public static function leak_status($sensor_state)
    {
        return $sensor_state->data->state->state;
    }

    public static function battery_check($sensor_state)
    {
        return $sensor_state->data->state->battery;
    }

    public static function firmware_check($sensor_state)
    {
        return $sensor_state->data->state->version;
    }

    public static function temperature_check($sensor_state)
    {
        //Note: I would apply a 9 degree offset for the acutal ambient temperature

        return $sensor_state->data->state->devTemperature;
    }
}
