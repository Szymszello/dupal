<?php
/**
 * @file
 * Contains \Drupal\my_event_subscriber\EventSubscriber\MyEventSubscriber.
 */

namespace Drupal\my_event_subscriber\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Event Subscriber MyEventSubscriber.
 */
class MyEventSubscriber implements EventSubscriberInterface
{

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        // For this example I am using KernelEvents constants (see below a full list).
        $events[KernelEvents::TERMINATE][] = ['onRespond',28];
        return $events;
    }

    /**
     * Code that should be triggered on event specified
     */

    public function onRespond(FilterResponseEvent $event)
    {
        // The RESPONSE event occurs once a response was created for replying to a request.
        // For example you could override or add extra HTTP headers in here
        $response = $event->getResponse();
        $response->headers->set('X-Custom-Header', 'MyValue');
    }

}