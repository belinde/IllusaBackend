<?php
/**
 * The content of this file is CONFIDENTIAL.
 * User: franco
 * Date: 04/03/18
 * Time: 11.20
 */

namespace App\EventSubscriber;


use App\Exception\InvalidJsonContentException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class JsonRequestSupport
 * @package App\EventSubscriber
 */
class JsonRequestSupport implements EventSubscriberInterface
{
    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 4096],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        if ($event->isMasterRequest() and 'json' === $request->getContentType()) {
            $content = $request->getContent();
            if ($content) {
                $data = json_decode($content, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new InvalidJsonContentException('Unable to parse request: malformed JSON');
                }
                if (is_array($data)) {
                    $request->request->replace($data);
                }
            }
        }
    }
}
