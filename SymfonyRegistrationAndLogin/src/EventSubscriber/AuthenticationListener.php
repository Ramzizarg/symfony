<?php
// src/EventSubscriber/AuthenticationListener.php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthenticationListener implements EventSubscriberInterface
{
    private $router;
    private $security;

    public function __construct(UrlGeneratorInterface $router, Security $security)
    {
        $this->router = $router;
        $this->security = $security;
    }

    public function onInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();

        // Check if the user is blocked
        if ($user instanceof UserInterface && $user->isBlocked()) {
            // User is blocked, do not subscribe to events
            return;
        }

        // User is not blocked, proceed with event subscription logic


    }

    public static function getSubscribedEvents()
    {
        // The static method required by EventSubscriberInterface
        return [
            SecurityEvents::INTERACTIVE_LOGIN => 'onInteractiveLogin',
            // Add other events as needed
        ];
    }
}
