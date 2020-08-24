<?php
namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

/**
 * Class RequestSubscriber
 * @package App\EventSubscriber
 */
class RequestSubscriber implements EventSubscriberInterface
{


    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => 'onKernelRequest',
        ];
    }

    /**
     * @param RequestEvent $event
     */
    public function onKernelRequest(
        RequestEvent $event
    )
    {

    }
}
