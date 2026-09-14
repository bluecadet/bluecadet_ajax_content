<?php

namespace Drupal\Tests\bluecadet_ajax_content\Kernel\Controller;

use Drupal\bluecadet_ajax_content_example\Controller\SimpleExample;
use Drupal\Core\Render\RenderContext;
use Drupal\KernelTests\KernelTestBase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Tests the SimpleExample controller.
 *
 * @group bluecadet_ajax_content
 */
class SimpleExampleTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'system',
    'bluecadet_ajax_content',
    'bluecadet_ajax_content_example',
  ];

  /**
   * The controller under test.
   *
   * @var \Drupal\bluecadet_ajax_content_example\Controller\SimpleExample
   */
  protected SimpleExample $controller;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->controller = $this->container->get('class_resolver')
      ->getInstanceFromDefinition(SimpleExample::class);
  }

  /**
   * Tests the example page build array.
   */
  public function testBuild(): void {
    $build = $this->controller->build(Request::create('/'));

    $this->assertSame('item_list', $build['example-nav']['#theme']);
    $this->assertCount(3, $build['example-nav']['#items']);
    $this->assertStringContainsString('data-ajax-now="/ajax-api/simple-example"', $build['center-content']['#markup']);
    $this->assertContains('bluecadet_ajax_content/content-ajaxing', $build['center-content']['#attached']['library']);
  }

  /**
   * Tests the AJAX response used to replace the placeholder markup.
   */
  public function testAjaxResponse(): void {
    $renderer = $this->container->get('renderer');
    $response = $renderer->executeInRenderContext(new RenderContext(), function () {
      return $this->controller->ajaxResponse(Request::create('/'));
    });

    $this->assertSame('text/html; charset=utf-8', $response->headers->get('Content-type'));
    $content = $response->getContent();
    $this->assertStringContainsString('Ajaxed Paragraph 1.', $content);
    $this->assertStringContainsString('Ajaxed Paragraph 2.', $content);
    $this->assertStringContainsString('Ajaxed Paragraph 3.', $content);
  }

}
