<?php

namespace libraries;

class SendMail
{

    private $CharSet = 'utf-8';

    private $SMTPDebug = 0;

    private $Debugoutput = 'html';

    private $Host = 'ssl://smtp.mail.ru';

    private $SMTPSecure = 'SSL';

    private $Port = 465;

    private $SMTPAuth = true;

    private $Username = 'meters_fabric@mail.ru';

    private $Password = '6sGvt8pEpr2pNdkn3adw';

    private $_FromEmail = 'meters_fabric@mail.ru';

    private $_FromName = 'Интернет Магазин МЕТРЫ';

    private $_address = [];

    private $_files = [];

    private $_mailBody = '';

    private $_templatePath = '';

    private $_lastError = '';

    public function __construct($options = []){

        if($options && is_iterable($options)){

            foreach ($options as $name => $value){

                if(property_exists($this, '_' . $name)){

                    $name = '_' . $name;

                }

                $this->$name = $value;

            }

        }

    }

    public function setTemplatesPath($templatePath = ''){

        if(!$templatePath){

            $this->_templatePath = $_SERVER['DOCUMENT_ROOT'] . PATH . TEMPLATE . 'include/emailTemplates';

        }else{

            $this->_templatePath = strpos($templatePath, $_SERVER['DOCUMENT_ROOT'] . PATH) !== false
                ? $templatePath : $_SERVER['DOCUMENT_ROOT'] . PATH . $templatePath;

        }

        $this->_templatePath = preg_replace('/\/{2,}/', '/', $this->_templatePath);

        !preg_match('/\/$/', $this->_templatePath) && $this->_templatePath .= '/';

    }

    public function getTemplate($templateFilePath){

        $template = '';

        if($templateFilePath){

            if(!$this->_templatePath){

                $this->setTemplatesPath();

            }

            $templateFilePath = preg_replace('/\/{2,}/', '/', $this->_templatePath . $templateFilePath);

            if(!preg_match('/\.[a-z]{1,5}$/', $templateFilePath)){

                $templateFilePath .= '.php';

            }

            if(is_readable($templateFilePath)){

                $template = file_get_contents($templateFilePath);

            }

        }

        return $template;

    }

    public function setTemplate($name, $value, $template = '', $templateFilePath = '', $setMailBody = true){

        $checkTemplate = $this->getTemplate($templateFilePath);

        $checkTemplate && $template = $checkTemplate;

        $template = preg_replace('/#' . preg_replace('/\-/', '\-', preg_quote($name)) . '#/', $value, $template);

        if($setMailBody){

            $this->setMailBody($template);

        }

        return $template;

    }

    public function setTemplateFromArray($arr, $template = '', $templateFilePath = '', $setMailBody = true){

        if(is_iterable($arr)){

            foreach ($arr as $name => $value){

                if(!$template && ($checkTemplate = $this->getTemplate($templateFilePath))){

                    $template = $checkTemplate;

                    $templateFilePath = '';

                }

                $template = $this->setTemplate($name, $value, $template, $templateFilePath, false);

            }

            if($setMailBody){

                $this->setMailBody($template);

            }

        }

        return $template;

    }

    public function setMailBody($template){

        $this->_mailBody .= $template;

    }

    public function addFile($path, $name){

        $this->_files[$path] = $name;

    }

    public function send($email = '', $subject = 'Re: заказ интеррнет магазина МЕТРЫ'){

        require_once $_SERVER['DOCUMENT_ROOT'] . PATH . 'libraries/PHPMailer/PHPMailerAutoload.php';

        $sender = new \PHPMailer;

        $sender->isSMTP();

        if(!is_array($this->_address)){

            $this->_address = (array)$this->_address;

        }

        if($email){

            foreach ((array)$email as $item){

                $this->_address[] = $item;

            }

        }

        foreach ($this as $name => $value){

            if(!preg_match('/^_/', $name)){

                $sender->$name = $value;

            }

        }

        $sender->setFrom($this->_FromEmail, $this->_FromName ?: $_SERVER['HTTP_HOST']);

        foreach ($this->_address as $item){

            $sender->addAddress($item);

        }

        if(!empty($this->_files)){

            foreach ($this->_files as $path => $name){

                $sender->addAttachment($path, $name);

            }

        }

        if(!empty($subject)){

            $sender->Subject = $subject;

        }

        if(!preg_match('/<\/html>\s*$/', $this->_mailBody)){

            $this->_mailBody = '<html><body>' . $this->_mailBody . '</body></html>';

        }

        $sender->msgHTML($this->_mailBody);

        $this->_lastError = '';

        if($sender->send()){

            $this->_mailBody = '';

            return true;

        }

        $this->_lastError = $sender->ErrorInfo;

        return false;

    }

    public function getLastError(){

        return $this->_lastError;

    }

}
