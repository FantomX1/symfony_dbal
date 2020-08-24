<?php

namespace App\EventSubscriber;

use App\Repository\CountryRepository;
use App\Services\FlashMessageService;
use Doctrine\DBAL\Driver\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class TwigEventSubscriber
 * @package App\EventSubscriber
 */
class TwigEventSubscriber implements EventSubscriberInterface
{

    /**
     * @var FlashMessageService
     */
    public $flashMessageService;

    /**
     * TwigEventSubscriber constructor.
     * @param FlashMessageService $flashMessageService
     * @param Connection $connection
     * @param UrlGeneratorInterface $router
     */
    public function __construct(
        FlashMessageService $flashMessageService,
        Connection $connection,
        # Router itself is not configured as a service
        # Router $router
        UrlGeneratorInterface $router
    )
    {
        $this->router = $router;
        $this->flashMessageService = $flashMessageService;
        $this->connection = $connection;
    }

    /**
     * @param ControllerEvent $event
     */
    public function onKernelController(ControllerEvent $event)
    {
        $controller = $event->getController();


        if (!array_key_exists(0,$controller) || !$controller[0] instanceof AbstractController) {
            return;
        }


        $connection   = $this->connection;
        $flashMessage = $this->flashMessageService;
        $router       = $this->router;

        // $h            = $event->getKernel();
        // $request      = $event->getKernel()->getRequest()
        $request      = $event->getRequest();

        if ($request->get('new_country')) {

            //FlashMessage::add("Item added");
            // @TODO: still duplicated

            //  Service  not found: even though it exists in the app's container, the container inside "App\Controller\HomeController" is a smaller service locator that only knows about the
            //$this->get('flashMessage')->add("Item added");
            $flashMessage->add("Item added");

            $rep = new CountryRepository($connection);

            $rep->insert(
                $request->get('name'),
                $request->get('population')
            );


            $redirectUrl = $router->generate('home');
            //$controller[0]->get
            $event->setController(function() use ($redirectUrl) {
                return new RedirectResponse($redirectUrl);
            });

            //return $controller[0]->redirectToRoute('home');
        }
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'kernel.controller' => 'onKernelController',
        ];
    }
}
