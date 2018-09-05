<?php

// ref. https://drupal.stackexchange.com/questions/231195/returning-alternate-http-codes-for-unpublished-content-in-drupal-8#answer-231263

namespace Drupal\queenslaw_unpublished_404\EventSubscriber;

use Drupal\Core\EventSubscriber\HttpExceptionSubscriberBase;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class QueensLawUnpublished404Subscriber extends HttpExceptionSubscriberBase {

  protected static function getPriority() {
    // set priority higher than 50 if you want to log "page not found"
    return 0;
  }

  protected function getHandledFormats() {
    return ['html'];
  }

  public function on403(GetResponseForExceptionEvent $event) {
    $request = $event->getRequest();
    if ($request->attributes->get('_route') == 'entity.node.canonical') {
      if ($node = $request->attributes->get('node')) {
        if (!$node->isPublished()) $event->setException(new NotFoundHttpException());
      }
    }
  }

}