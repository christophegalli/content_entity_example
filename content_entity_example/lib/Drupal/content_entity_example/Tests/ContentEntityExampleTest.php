<?php

/**
 * @file
 * Test cases for Content Entity Example Module.
 */

namespace Drupal\content_entity_example\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Class ContentEntityExampleTest.
 * @package Drupal\content_entity_example\Tests
 *
 * @ingroup content_entity_example
 */
class ContentEntityExampleTest extends WebTestBase {

  public static $modules = array('content_entity_example', 'block');

  protected $web_user;

  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return array(
      'name' => 'Content Entity Example tests',
      'description' => 'Tests the basic functions of the Content Entity Example module.',
      'group' => 'Content Entity Example',
    );
  }

  public function setUp() {
    parent::setUp();

    $this->web_user = $this->drupalCreateUser(array('view content_entity_example entity'));
    $this->drupalPlaceBlock('system_menu_block:tools', array());
  }

  /**
   * Basic tests for Content Entity Example.
   */
  public function testContentEntityExample() {

     $this->assertNoText('Content Entity Example Listing');

    $this->drupalLogin($this->web_user);

    $this->assertLink('Content Entity Example Listing');

  }
}
