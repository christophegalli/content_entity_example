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

  protected $webUser;

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

  /**
   * Set up instance for starting the test.
   */
  public function setUp() {
    parent::setUp();

    $this->webUser = $this->drupalCreateUser(array(
     'add contact entity',
     'edit contact entity',
     'view contact entity',
     'delete contact entity',
     'administer contact entity'));
    $this->drupalPlaceBlock('system_menu_block:tools', array());
  }

  /**
   * Basic tests for Content Entity Example.
   */
  public function testContentEntityExample() {

    // Anonymous User should not see the link to the listing.
    $this->assertNoText(t('Content Entity Example: Contacts Listing'));

    $this->drupalLogin($this->webUser);

    // Web_user user has the right to view listing.
    $this->assertLink(t('Content Entity Example: Contacts Listing'));

    $this->clickLink(t('Content Entity Example: Contacts Listing'));

    // WebUser can add entity content.
    $this->assertLink(t('Add Contact'));

    $this->clickLink(t('Add Contact'));

    $this->assertFieldByName('name[0][value]', '', 'Name Field, empty');
    $this->assertFieldByName('name[0][value]', '', 'First Name Field, empty');
    $this->assertFieldByName('name[0][value]', '', 'Gender Field, empty');

    $user_ref = $this->webUser->name->value . ' (' . $this->webUser->id() . ')';
    $this->assertFieldByName('user_id[0][target_id]', $user_ref, 'User ID reference field points to web_user');

    // Post content, save an instance. Go back to list after saving.
    $edit = array(
      'name[0][value]' => 'test name',
      'first_name[0][value]' => 'test first name',
      'gender' => 'male',
    );
    $this->drupalPostForm(NULL, $edit, t('Save'));

    // Entity listed.
    $this->assertLink(t('Edit'));
    $this->assertLink(t('Delete'));

    $this->clickLink('test name');

    // Entity shown.
    $this->assertText(t('test name'));
    $this->assertText(t('test first name'));
    $this->assertText(t('male'));
    $this->assertLink(t('Add Contact'));
    $this->assertLink(t('Edit'));
    $this->assertLink(t('Delete'));

    // Delete the entity.
    $this->clickLink('Delete');

    // Confirm deletion.
    $this->assertLink(t('Cancel'));
    $this->drupalPostForm(NULL, array(), 'Delete');

    // Back to list, must be empty.
    $this->assertNoText('test name');

    // Settings page.
    $this->drupalGet('admin/structure/content_entity_example_contact_settings');
    $this->assertText(t('Contact Settings'));

  }
}
