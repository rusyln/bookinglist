<?php

namespace Drupal\bookinglist\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\taxonomy\Entity\Term;

class BookinglistController extends ControllerBase {

  public function build() {
    $user = \Drupal::currentUser();
    $uid = $user->id();

    $query = \Drupal::entityQuery('node')
      ->condition('status', 1)
      ->condition('type', 'booking')
      ->condition('uid', $uid)
      ->accessCheck(FALSE);
    $nids = $query->execute();

    $header = [
      'title' => $this->t('Title'),
      'start_date' => $this->t('Start Date'),
      'end_date' => $this->t('End Date'),
      'room' => $this->t('Room'),
    ];

    $rows = [];

    if (!empty($nids)) {
      foreach ($nids as $nid) {
        $node = Node::load($nid);
        $start_date = $node->get('field_field_start_datetime')->value;
        $end_date = $node->get('field_end_datetime')->value;
        $room_tid = $node->get('field_rooms')->target_id;
        $room_term = Term::load($room_tid);
        $room_name = $room_term ? $room_term->getName() : '';


        // Convert to DateTime objects
        $start_datetime = new \DateTime($start_date);
        $end_datetime = new \DateTime($end_date);

        // Format the dates
        $formatted_start_date = $start_datetime->format('F d, Y H:i');
        $formatted_end_date = $end_datetime->format('F d, Y H:i');


        $url = Url::fromRoute('entity.node.canonical', ['node' => $nid]);
        $link = Link::fromTextAndUrl($node->getTitle(), $url)->toString();

        $rows[] = [
          'title' => $link,
          'start_date' => $formatted_start_date,
          'end_date' => $formatted_end_date,
          'room' => $room_name,
        ];
      }
    }

    $build['content'] = [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#empty' => $this->t("You don't have a current conference room booking."),
    ];

    return $build;
  }

}