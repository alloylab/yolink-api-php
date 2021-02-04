<?php

namespace YoLink;

class Auth
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

        $authPaylod['url'] = $this->yolink_api;
        $authPaylod['SecKey'] = $this->SecKey;
        $authPaylod['header'] = $this->auth_header();

        return $authPaylod;
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

    private function auth_header()
    {
        $auth_header = array(
            'Accept: application/json',
            'Content-Type: application/json',
            'ktt-ys-brand: yolink',
            'KTT-CSID: ' . $this->CSID,
        );

        return $auth_header;
    }
}
