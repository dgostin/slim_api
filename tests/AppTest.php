<?php

use PHPUnit\Framework\TestCase;

class AppTest extends TestCase {
    
    protected $api_url = "http://penn.test";

    public function testGetUsers() {
        $url = $this->api_url."/users";
        $response = $this->callAPI('GET', $url);
        $this->assertIsObject(json_decode($response)[0]);
    }

    public function testAddUser() {
        $url = $this->api_url."/users";
        $response = $this->callAPI('POST', $url, '{"name": "Joe Campbell", "email": "joe@jj.com", "points_balance": 0}');
        $this->assertEquals('true', $response);
    }

    function callAPI($method, $url, $data=null){

        $curl = curl_init();

        switch ($method){
           case "POST":
              curl_setopt($curl, CURLOPT_POST, 1);
              if ($data)
                 curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
              break;
           case "PUT":
              curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
              if ($data)
                 curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
              break;
           default:
              if ($data)
                 $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
           'Content-Type: application/json',
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $result = curl_exec($curl);
        if(!$result){die("Connection Failure");}
        curl_close($curl);
        return $result;
     }

}