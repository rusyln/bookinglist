<?php

namespace Drupal\bookinglist\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Returns responses for BookingList routes.
 */
class BookinglistController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {
    $user = \Drupal::currentUser();
    $uid = $user->id();

    $query = \Drupal::entityQuery('node')
      ->condition('status', 1)
      ->condition('type', 'booking') // Change 'booking' to your content type
      ->condition('uid', $uid);
    $nids = $query->execute();

    $build['content'] = [
      '#theme' => 'item_list',
      '#items' => [],
    ];

    foreach ($nids as $nid) {
      $node = Node::load($nid);
      $url = Url::fromRoute('entity.node.canonical', ['node' => $nid]);
      $link = Link::fromTextAndUrl($node->getTitle(), $url);
      $build['content']['#items'][] = $link->toRenderable();
    }

    return $build;
  }

