<?php


namespace App\Services;


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
class FlashMessageService
{


    /**
     * @var
     */
    private  $messages;


    public function add($message)
    {

        @session_start();


        $_SESSION['flash_messages'] = $this->messages[] = $message;
        // when static destructor non existant, sync each time
    }


    public  function get()
    {

        @session_start();

        if (empty($this->messages)) {
            $this->messages = $_SESSION['flash_messages'] ?? [];
        }

        $_SESSION['flash_messages']  = [];

        return $this->messages;

        //return static::$messages;

    }


    public function __destruct()
    {

        // $_SESSION['flash_messages'] = static::$messages;

    }

}






