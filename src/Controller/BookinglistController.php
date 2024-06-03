<?php

namespace Drupal\bookinglist\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for BookingList routes.
 */
class BookinglistController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works!'),
    ];

    return $build;
  }

}
