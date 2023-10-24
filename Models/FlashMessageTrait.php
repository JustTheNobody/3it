<?php

namespace Models;

trait FlashMessageTrait
{
    public static function setFlash($type, $message)
    {
        print_r($type, $message);
        if (!isset($_SESSION['flash_messages'])) {
            $_SESSION['flash_messages'] = [];
        }

        $_SESSION['flash_messages'][$type] = $message;
    }

    public static function getFlashByType($type)
    {
        if (isset($_SESSION['flash_messages'][$type])) {
            $messages = $_SESSION['flash_messages'][$type];
            unset($_SESSION['flash_messages'][$type]);
            return $messages;
        }

        return [];
    }

    public static function getFlash()
    {
        if (isset($_SESSION['flash_messages'])) {
            $messages = $_SESSION['flash_messages'];
            unset($_SESSION['flash_messages']);
            return $messages;
        }

        return [];
    }
}
