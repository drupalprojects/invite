<?php

namespace Drupal\invite\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Active user list controller.
 */
class InviteList extends ControllerBase {

  /**
   * Returns the active user list output.
   *
   * @return array
   *   A renderable array.
   */
  public function view() {

    $header = [
      ['data' => $this->t('Sender')],
      ['data' => $this->t('E-mail')],
    ];

    $db = \Drupal::database();

    $query = $db->select('invite', 'i');
    $query->fields('ufd', ['mail']);
    $query->fields('ie', ['field_invite_email_address_value']);
    $query->leftJoin('users', 'u', 'i.user_id = u.uid');
    $query->leftJoin('users_field_data', 'ufd', 'u.uid = ufd.uid');
    $query->leftJoin('invite__field_invite_email_address', 'ie', 'i.id = ie.entity_id');
    $query->orderBy('i.id', 'desc');

    $query = $query->extend('Drupal\Core\Database\Query\PagerSelectExtender');
    $query->limit(20);
    $result = $query->execute();

    $rows = [];
    foreach ($result as $row) {
      $rows[] = [
        'data' => [
          'mail' => $row->mail,
          'field_invite_email_address_value' => $row->field_invite_email_address_value,
        ],
      ];
    }

    $output['table'] = [
      '#theme' => 'table',
      '#header' => $header,
      '#rows' => $rows,
    ];

    $output['pager'] = [
      '#type' => 'pager',
    ];

    return $output;
  }

}
