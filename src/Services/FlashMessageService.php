<?php


namespace App\Services;


// @TODO: Dynamically, not to be dependent
use Symfony\Component\HttpFoundation\Session\Session;

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
     * @var Session
     */
    private $session;

    public function __construct()
    {

        // copy to submethods, so it is less time in memory
        $this->session = new Session();
    }


    /**
     * @var
     */
    private  $messages;


    public function add($message)
    {

        $this->messages[] = $message;
        $this->session->set('flash_messages', $this->messages);

    }


    public  function get()
    {

        //@session_start();

        if (empty($this->messages)) {
            $this->messages = $this->session->get('flash_messages');
        }

        $this->session->set('flash_messages',[]);
        return $this->messages;
    }


    public function __destruct()
    {

        // $_SESSION['flash_messages'] = static::$messages;


        // @TODO: doesn't work in destructor needs to set also in add each time above
        //$this->session->set('flash_messages', $this->messages);

    }

}






