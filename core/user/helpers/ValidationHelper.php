<?php

namespace core\user\helpers;

trait ValidationHelper
{

    protected function emptyField($value, $answer)
    {

        $value = $this->clearStr($value);

        if (empty($value)) {

            $this->sendError('Не заполенено поле', $answer);

        }

        return $value;

    }

    protected function numericField($value, $answer, $count = null)
    {

        $value = preg_replace('/\D/', '', $this->clearStr($value));

        if (!$value) {

            $this->sendError('Не корректное поле', $answer);

        }

        if ($count) {

            if (strlen($value) !== $count) {

                $this->sendError('Длинна поля ' . $answer . ' должна содержать ',
                    $count . ' ' . $this->wordsForCounter($count, ['Символов', 'Символ', 'Символа']));

            }

        }

        return $value;

    }

    protected function phoneField($value, $answer = null)
    {

        $value = preg_replace('/\D/', '', $this->clearStr($value));

        if (strlen($value) === 11) {

            $value = preg_replace('/^8/', '7', $value);

        }

        return $value;

    }

    protected function emailField($value, $answer)
    {

        $value = $this->clearStr($value);

        if (!preg_match('/^[\w\-\.]+@[\w\-]+\.[\w\-]+/i', $value)){

            $this->sendError('Не корректный формат email в поле ', $answer);

        }

        return $value;

    }

    protected function sendError($text, $answer = '', $logMessage = null, $fileName = 'order_error_log.txt', $class = 'error'){

        $_SESSION['res']['answer'] = '<div class="' . $class . '">' . ($class === 'error' ? $this->translateEl($text) : $text) . ' ' . $answer . '</div>';

        if($logMessage){

            if(!is_string($logMessage)){

                $logMessage = $this->translateEl($text) . ' ' . $answer;

            }

            $this->writeLog($logMessage, $fileName);

        }

        if($class === 'error'){

            $this->addSessionData();

        }

    }

    protected function sendSuccess($text, $answer = '', $logMessage = null, $fileName = 'order_error_log.txt', $class = 'success'){

        $this->sendError($text, $answer, $logMessage, $fileName, $class);

    }

}