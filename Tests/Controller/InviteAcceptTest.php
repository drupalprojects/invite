<?php

namespace Drupal\invite\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * @todo.
 *
 * Provides automated tests for the invite module.
 */
class InviteAcceptTest extends WebTestBase {
  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return array(
      'name' => "Accept invite.",
      'description' => 'Test that accepting an invitation creates a user.',
      'group' => 'Invite.',
    );
  }

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
  }

  /**
   * Tests invite functionality.
   */
  public function testInviteAccept() {
    $this->assertEquals(TRUE, TRUE, 'Test Unit...');
  }

}
