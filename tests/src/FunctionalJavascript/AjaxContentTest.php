<?php

namespace Drupal\Tests\bluecadet_ajax_content\FunctionalJavascript;

use Drupal\Core\Url;
use Drupal\FunctionalJavascriptTests\WebDriverTestBase;

/**
 * Tests Ajax content loading functionality.
 *
 * @group bluecadet_ajax_content
 */
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
   * Tests immediate Ajax content loading.
   */
  public function testAjaxContentLoadImmediate(): void {
    $url = Url::fromRoute('bluecadet_ajax_content_example.simple_example_immediate');
    $this->drupalGet($url);

    $session_assert = $this->assertSession();

    $session_assert->waitForElementVisible('css', '.ajax-now--loaded');
    $session_assert->waitForElementVisible('css', '.ajax-now--loaded p');

    $this->assertAjaxParagraphsPresent();
  }

  /**
   * Tests scroll-triggered Ajax content loading.
   */
  public function testAjaxContentLoadOnScroll(): void {
    $url = Url::fromRoute('bluecadet_ajax_content_example.simple_example_scroll');
    $this->drupalGet($url);

    $session_assert = $this->assertSession();
    $session_assert->waitForElementVisible('css', '[data-ajax-scroll]');

    // Bring the observed element into view so IntersectionObserver can fire.
    $this->scrollElementIntoView('[data-ajax-scroll]');

    $session_assert->waitForElementVisible('css', '[data-ajax-scroll].loaded p');

    $this->assertAjaxParagraphsPresent();
  }

  /**
   * Tests Ajax commands content replacement.
   */
  public function testAjaxCommandsLoad(): void {
    $url = Url::fromRoute('bluecadet_ajax_content_example.ajax_commands_example_scroll');
    $this->drupalGet($url);

    $session_assert = $this->assertSession();
    $session_assert->waitForElementVisible('css', '#to-be-replaced-1');

    // Bring the observed element into view so IntersectionObserver can fire.
    $this->scrollElementIntoView('#to-be-replaced-1');

    $session_assert->waitForElementVisible('css', '#to-be-replaced-1 p');
    $session_assert->elementTextContains('css', '#to-be-replaced-1', 'Ajaxed Paragraph 1.');
    $session_assert->elementTextContains('css', '#to-be-replaced-1', 'Ajaxed Paragraph 2.');
    $session_assert->elementTextContains('css', '#to-be-replaced-1', 'Ajaxed Paragraph 3.');
  }

  /**
   * Scrolls a CSS selector into the viewport to trigger observer-based loading.
   */
  protected function scrollElementIntoView(string $selector): void {
    $selector = addslashes($selector);
    $this->getSession()->executeScript("const el = document.querySelector(\"$selector\"); if (el) { el.scrollIntoView({block: 'center'}); }");
  }

  /**
   * Asserts the expected paragraphs are present on the page.
   */
  protected function assertAjaxParagraphsPresent(): void {
    $session_assert = $this->assertSession();

    $session_assert->pageTextContains('Ajaxed Paragraph 1.');
    $session_assert->pageTextContains('Ajaxed Paragraph 2.');
    $session_assert->pageTextContains('Ajaxed Paragraph 3.');
  }

}
