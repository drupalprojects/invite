<?php

/**
 * @file
 * Token integration functions for invite module.
 */

/**
 * Implements hook_tokens().
 */
function invite_tokens($type, $tokens, array $data = array(), array $options = array()) {
  $replacements = array();
  if ($type == 'invite' && !empty($data['invite'])) {
    $invite = $data['invite'];
    foreach ($tokens as $name => $original) {
      switch ($name) {
        case 'inviter':
          $replacements[$original] = check_plain($invite->inviter->name);
          break;

        case 'inviter-raw':
          $replacements[$original] = $invite->inviter->name;
          break;
        
        case 'invite-mail':
          $replacements[$original] = $invite->email;
          break;

        case 'invite-message':
          $replacements[$original] = check_plain($invite->data['message']);
          break;
        
        case 'invite-message-raw':
          $replacements[$original] = $invite->data['message'];
          break;

        case 'join-link':
          $replacements[$original] = url('invite/accept/' . $invite->code, array('absolute' => TRUE));
          break;
        
      }
    }
  }

  return $replacements;
}

/**
 * Implements hook_token_list().
 */
function invite_token_info() {
  $types['invite'] = array(
    'name' => t("Invite"), 
    'description' => t("Tokens related to invites."), 
    'needs-data' => 'invite',
  );

  $tokens['inviter'] = array(
    'name' => t('Inviter'),
    'description' => t('The user name of the inviter.'),
  );
  
  $tokens['inviter-raw'] = array(
    'name' => t('Inviter (raw)'),
    'description' => t('The user name of the inviter. WARNING - raw user input.'),
  );

  $tokens['invite-mail'] = array(
    'name' => t('Invite mail'),
    'description' => t('The e-mail address of the invited user.'),
  );
  
  $tokens['invite-message'] = array(
    'name' => t('Invite message'),
    'description' => t('The personal message for the invitee.'),
  );
  
  $tokens['invite-message-raw'] = array(
    'description' => t('The personal message for the invitee as unfiltered text. WARNING - raw user input.'),
  );
  
  $tokens['join-link'] = array(
    'description' => t('The link to the registration page, including the invitation code.'),
  );

  return array(
    'types' => $types,
    'tokens' => array('invite' => $tokens),
  );
}

/**
 * For a given context, builds a formatted list of tokens and descriptions
 * of their replacement values.
 *
 * @param type
 *   The token types to display documentation for. Defaults to 'all'.
 * @param prefix
 *   The prefix your module will use when parsing tokens. Defaults to '['
 * @param suffix
 *   The suffix your module will use when parsing tokens. Defaults to ']'
 * @return
 *   An HTML table containing the formatting docs.
 */
function theme_invite_token_help($variables) {
  $type = !empty($variables['type']) ? $variables['type'] : 'all';
  $prefix = !empty($variables['prefix']) ? $variables['prefix'] : '[';
  $suffix = !empty($variables['suffix']) ? $variables['suffix'] : ']';

  token_include();

  // @see http://drupal.org/node/127072
  $full_list = array();
  foreach ((array) $type as $t) {
    $full_list = array_merge($full_list, token_get_list($t));
  }

  $headers = array(t('Token'), t('Replacement value'));
  $rows = array();
  foreach ($full_list as $key => $category) {
    $rows[] = array(array('data' => drupal_ucfirst($key) . ' ' . t('tokens'), 'class' => 'region', 'colspan' => 2));
    foreach ($category as $token => $description) {
      $row = array();
      $row[] = $prefix . $token . $suffix;
      $row[] = $description;
      $rows[] = $row;
    }
  }

  $output = theme('table', array('header' => $headers, 'rows' => $rows, 'attributes' => array('class' => 'description')));
  return $output;
}

