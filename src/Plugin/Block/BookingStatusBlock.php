<?php

namespace Drupal\bookinglist\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;

/**
 * Provides a 'Booking Status' Block.
 *
 * @Block(
 *   id = "booking_status_block",
 *   admin_label = @Translation("Booking Status Block"),
 *   category = @Translation("Booking"),
 * )
 */
class BookingStatusBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $confirmed_count = $this->getBookingCount('confirmed');
    $pending_count = $this->getBookingCount('pending');

    return [
        '#theme' => 'table',
        '#header' => ['Booking Status', 'Count'],
        '#rows' => [
            ['Confirmed Bookings', $confirmed_count],
            ['Pending Bookings', $pending_count],
        ],
        '#attributes' => [
            'style' => 'margin: 10px 10px 10px;',
        ],
    ];
}

  /**
   * Get the count of bookings by status.
   *
   * @param string $status
   *   The booking status (e.g., 'confirmed', 'pending').
   *
   * @return int
   *   The count of bookings with the given status.
   */
  private function getBookingCount($status) {
    $query = \Drupal::entityQuery('node')
      ->condition('type', 'booking')
      ->condition('field_booking_status', $status)
      ->accessCheck(FALSE);
    $nids = $query->execute();

    return count($nids);
  }

}
