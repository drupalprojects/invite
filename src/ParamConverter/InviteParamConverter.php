<?php
/**
 * @file
 * Contains Drupal\invite\ParamConverter\InviteParamConverter.
 */
namespace Drupal\invite\ParamConverter;

use Drupal\Core\ParamConverter\ParamConverterInterface;
use Drupal\invite\Entity\Invite;
use Symfony\Component\Routing\Route;

class InviteParamConverter implements ParamConverterInterface {

  public function applies($definition, $name, Route $route) {
    return (!empty($definition['type']) && $definition['type'] == 'reg_code');
  }

  public function convert($reg_code, $definition, $name, array $defaults) {
    $invite = \Drupal::entityQuery('invite')
      ->condition('reg_code', $reg_code)
      ->execute();
    return Invite::load(reset($invite));
  }
}