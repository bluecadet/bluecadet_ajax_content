<?php

namespace Drupal\bluecadet_ajax_content\FunctionalJavascript;

use Drupal\Core\Url;
use Drupal\FunctionalJavascriptTests\WebDriverTestBase;
use Drupal\system\Entity\Action;

class AjaxContentTest extends WebDriverTestBase {
    /**
   * {@inheritdoc}
   */
  protected static $modules = ['bluecadet_ajax_content', 'bluecadet_ajax_content_example'];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    // $user = $this->drupalCreateUser(['administer actions']);
    // $this->drupalLogin($user);
  }

  /**
   * Tests action plugins with AJAX save their configuration.
   */
  public function testAjaxContentLoad() {
    // Simple example.
    $url = Url::fromRoute('bluecadet_ajax_content_example.simple_example_immediate');
    $this->drupalGet($url);

    $session_assert = $this->assertSession();

    $session_assert->assertWaitOnAjaxRequest();
    $page = $this->getSession()->getPage();

    $session_assert->pageTextContains('Ajaxed Paragraph 1.');
    $session_assert->pageTextContains('Ajaxed Paragraph 2.');
    $session_assert->pageTextContains('Ajaxed Paragraph 3.');


    // Scroll example.
    // $url = Url::fromRoute('bluecadet_ajax_content_example.simple_example_scroll');
    // $this->drupalGet($url);
    // // $this->assertSession()->assertWaitOnAjaxRequest();
    // $page = $this->getSession()->getPage();

    // $this->assertSession()->waitForElementVisible('css', 'div[data-ajax-scroll=*]');

    // $this->assertSession()->pageTextContains('Ajaxed Paragraph 1.');
    // $this->assertSession()->pageTextContains('Ajaxed Paragraph 2.');
    // $this->assertSession()->pageTextContains('Ajaxed Paragraph 3.');


    // $id = 'test_plugin';
    // $this->assertSession()->waitForElementVisible('named', ['button', 'Edit'])->press();
    // $this->assertSession()->waitForElementVisible('css', '[name="id"]')->setValue($id);

    // $page->find('css', '[name="having_a_party"]')
    //   ->check();
    // $this->assertSession()->waitForElementVisible('css', '[name="party_time"]');

    // $party_time = 'Evening';
    // $page->find('css', '[name="party_time"]')
    //   ->setValue($party_time);

    // $page->find('css', '[value="Save"]')
    //   ->click();

    // $url = Url::fromRoute('entity.action.collection');
    // $this->assertSession()->pageTextContains('The action has been successfully saved.');
    // $this->assertSession()->addressEquals($url);

    // // Check storage.
    // $instance = Action::load($id);
    // $configuration = $instance->getPlugin()->getConfiguration();
    // $this->assertEquals(['party_time' => $party_time], $configuration);

    // // Configuration should be shown in edit form.
    // $this->drupalGet($instance->toUrl('edit-form'));
    // $this->assertSession()->checkboxChecked('having_a_party');
    // $this->assertSession()->fieldValueEquals('party_time', $party_time);
  }
}
