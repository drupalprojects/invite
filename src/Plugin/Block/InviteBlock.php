<?php

namespace Drupal\invite\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\invite\Form\InviteBlockForm;

/**
 * Provides a 'InviteBlock' block.
 *
 * @Block(
 *  id = "invite_block",
 *  admin_label = @Translation("Invite block"),
 *  deriver = "Drupal\invite\Plugin\Derivative\InviteBlock"
 * )
 */
class InviteBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $block_id = $this->getDerivativeId();
    $build = [];
    $form = \Drupal::formBuilder()->getForm(new InviteBlockForm, $block_id);
    $build['form'] = $form;

    return $build;
  }

}
