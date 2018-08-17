<?php

namespace uqpay\payment\sdk\config;
require_once 'RSAconfig.php';
class baseConfig
{
    private $testMode = false;
    private $testRSA;
    private $productRSA;

    public function getRSA()
    {
        if ($this->isTestMode()) {
            return $this->testRSA;
        }
        return $this->productRSA;
    }

    public function isTestMode()
    {
        return $this->testMode;
    }

    public function __set($name, $value){
        $this->$name = $value;
    }

    //get方法
    public function __get($name){
        if(!isset($this->$name)){
            //未设置
            $this->$name = "";
        }
        return $this->$name;
    }
}
