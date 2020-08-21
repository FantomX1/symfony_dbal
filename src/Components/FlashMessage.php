<?php


namespace App\Components;


// @TODO: Dynamically, not to be dependent

/**
 * SessionInterface $session)
{
$session->set('foo', 'bar');
 *
 *
 *
 *
 * Class FlashMessage
 * @package App\Components
 */
class FlashMessage
{

    /**
     * @var
     */
    private static $messages;


    public static function add($message)
    {

        @session_start();


        static::$messages[] = $message;
        $_SESSION['flash_messages'] = static::$messages;
        $o=3;

        //$_SESSION['flash_messages'] = $message;

    }


    public static function get()
    {

        @session_start();

        if (empty(static::$messages)) {
            static::$messages = $_SESSION['flash_messages'] ?? [];
        }

        $_SESSION['flash_messages']  = [];

        return static::$messages;

        //return static::$messages;

    }


    public function __destruct()
    {

        // $_SESSION['flash_messages'] = static::$messages;

    }

}






