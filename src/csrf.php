<?php

    /**
     * Class csrf
     *
     * @author Baha Şener
     * @mail baha.sener@hotmail.com
     * @date 8 December 2022
     */

    class Csrf{

        public $config;

        public function __construct($config){

            $this->config = $config;

            if(!in_array('openssl', get_loaded_extensions())){
                $this->showError('OpenSSL extension must be installed in PHP.');
                die();
            }

            if(!isset($this->config['key']) || empty($this->config['key'])){
                $this->showError('You must specify a key value with the configuration.');
                die();
            }

            if(!isset($this->config['secret']) || empty($this->config['secret'])){
                $this->showError('You must specify a secret value with the configuration.');
                die();
            }

            if(empty($_SESSION['_csrf'])){
                $_SESSION['_csrf'] = $this->EncryptToken(bin2hex(random_bytes(32)));
            }

        }

        private function EncryptToken($data){

            if(isset($data)){

                $key = hash('sha256', $this->config['key']);
                $iv = substr(hash('sha256', $this->config['secret']), 0, 16);
                $encrypt = openssl_encrypt($data, 'AES-128-CBC', $key, 0, $iv);
                return $encrypt;

            }

        }

        public function Get(){
            return $_SESSION['_csrf'];
        }

        public function Check($token){

            $userToken = $this->EncryptToken($token);
            $systemToken = $this->EncryptToken($_SESSION['_csrf']);

            if(hash_equals($userToken, $systemToken)){
                unset($_SESSION['_csrf']);
                return true;
            }else{
                return false;
            }

        }

        public function Reset(){

            if(isset($_SESSION['_csrf'])){

                unset($_SESSION['_csrf']);

                if(!isset($_SESSION['_csrf'])){

                    $_SESSION['_csrf'] = $this->EncryptToken(bin2hex(random_bytes(32)));
                    if(isset($_SESSION['_csrf'])){
                        return true;
                    }else{
                        return false;
                    }

                }

            }else{

                if(empty($_SESSION['_csrf'])){

                    $_SESSION['_csrf'] = $this->EncryptToken(bin2hex(random_bytes(32)));
                    if(isset($_SESSION['_csrf'])){
                        return true;
                    }else{
                        return false;
                    }

                }else{
                    return false;
                }

            }

        }

        private function DecryptToken($data){

            if(isset($data)){

                $key = hash('sha256', $this->config['key']);
                $iv = substr(hash('sha256', $this->config['secret']), 0, 16);
                return openssl_decrypt($data, 'AES-128-CBC', $key, 0, $iv);

            }

        }

        private function showError($error){
            $this->errorTemplate($error);
        }

        private function errorTemplate($errorMsg, $title = null)
        {
            ?>
            <div class="php-encryption-error-msg-content">
                <div class="php-encryption-error-title">
                    <?= $title ? $title : __CLASS__ . ' Error:' ?>
                </div>
                <div class="php-encryption-error-msg"><?= $errorMsg ?></div>
            </div>
            <style>
                .php-encryption-error-msg-content {
                    padding: 15px;
                    border-left: 5px solid #c00000;
                    background: rgba(192, 0, 0, 0.06);
                    background: #f8f8f8;
                    margin-bottom: 10px;
                }

                .php-encryption-error-title {
                    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
                    font-size: 16px;
                    font-weight: 500;
                }

                .php-encryption-error-msg {
                    margin-top: 15px;
                    font-size: 14px;
                    font-family: Consolas, Monaco, Menlo, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, sans-serif;
                    color: #c00000;
                }
            </style>
            <?php
        }

    }