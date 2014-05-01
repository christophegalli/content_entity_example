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

  public static $modules = array('content_entity_example', 'block', 'entity_reference');

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

    $this->web_user = $this->drupalCreateUser(array(
     'add content_entity_example entity',
     'edit content_entity_example entity',
     'view content_entity_example entity',
     'delete content_entity_example entity',
     'administer content_entity_example entity'));
    $this->drupalPlaceBlock('system_menu_block:tools', array());
  }

  /**
   * Basic tests for Content Entity Example.
   */
  public function testContentEntityExample() {

    //  Anonymous User should not see the link to the listing.
    $this->assertNoText(t('Content Entity Example Listing'));

    $this->drupalLogin($this->web_user);

    // Web_user user has the right to view listing.
    $this->assertLink(t('Content Entity Example Listing'));

    $this->clickLink(t('Content Entity Example Listing'));

    // Web_user can add entity content.
    $this->assertLink(t('Add Content Entity Example Content'));

    $this->clickLink(t('Add Content Entity Example Content'));

    $this->assertFieldByName('name[0][value]','', 'Name Field, empty');

    $user_ref = $this->web_user->name->value . ' (' . $this->web_user->id() . ')';
    $this->assertFieldByName('user_id[0][target_id]', $user_ref,'User ID reference field points to web_user' );

    // Post content, save an instance. Go back to list after saving.
    $edit = array(
      'name[0][value]' => 'test name',
    );
    $this->drupalPostForm(NULL, $edit, t('Save'));

    // Entity listed.
    $this->assertLink(t('Edit'));
    $this->assertLink(t('Delete'));

    $this->clickLink('test name');

    // Entity shown.
    $this->assertText(t('test name'));
    $this->assertLink(t('Add Content Entity Example Content'));
    $this->assertLink(t('Edit'));
    $this->assertLink(t('Delete'));

    // Delete the entity.
    $this->clickLink('Delete');

    // Confirm deletion.
    $this->assertLink(t('Cancel'));
    $this->drupalPostForm(NULL,array(),'Delete');

    // Back to list, must be empty.
    $this->assertNoText('test name');


    // Settings page.
    $this->drupalGet('admin/structure/content_entity_example_settings');
    $this->assertText(t('ContentEntityExample Settings'));

  }
}
