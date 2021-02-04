<?php

namespace YoLink;

class LeakSensor
{
    public function getState($authPaylod, $targetDevice, $targetToken)
    {
        $post_json = json_encode(array(
            'method' => 'LeakSensor.getState',
            'time' => \YoLink\Helper::time_milliseconds(),
            'targetDevice' => $targetDevice,
            'token' => $targetToken
        ));

        $url = $authPaylod['url'];
        $auth_header = $authPaylod['header'];
        $auth_header['ys-sec'] = \YoLink\Helper::hashed_secKey($post_json, $authPaylod['SecKey']);

        $request = \YoLink\Client::post($url, $auth_header, $post_json);

        $sensor_data = json_decode($request);

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
}
