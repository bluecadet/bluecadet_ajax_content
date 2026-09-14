<?php

namespace Drupal\Tests\bluecadet_ajax_content\Kernel\Controller;

use Drupal\bluecadet_ajax_content_example\Controller\AjaxCommandsExample;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Render\RenderContext;
use Drupal\KernelTests\KernelTestBase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Tests the AjaxCommandsExample controller.
 *
 * @group bluecadet_ajax_content
 */
class AjaxCommandsExampleTest extends KernelTestBase {

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
   * @var \Drupal\bluecadet_ajax_content_example\Controller\AjaxCommandsExample
   */
  protected AjaxCommandsExample $controller;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->controller = $this->container->get('class_resolver')
      ->getInstanceFromDefinition(AjaxCommandsExample::class);
  }

  /**
   * Tests the example page build array.
   */
  public function testBuild(): void {
    $build = $this->controller->build(Request::create('/'));

    $this->assertSame('item_list', $build['example-nav']['#theme']);
    $this->assertCount(3, $build['example-nav']['#items']);
    $this->assertStringContainsString('id="to-be-replaced-1"', $build['center-content']['#markup']);
    $this->assertStringContainsString('data-ajax-commands="/ajax-api/ajax-commands-example"', $build['center-content']['#markup']);
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

    $commands = $response->getCommands();
    $this->assertCount(1, $commands);
    $this->assertSame((new ReplaceCommand('', ''))->render()['command'], $commands[0]['command']);
    $this->assertSame('#to-be-replaced-1', $commands[0]['selector']);
    $this->assertStringContainsString('Ajaxed Paragraph 1.', $commands[0]['data']);
    $this->assertStringContainsString('Ajaxed Paragraph 2.', $commands[0]['data']);
    $this->assertStringContainsString('Ajaxed Paragraph 3.', $commands[0]['data']);
    $this->assertContains('bluecadet_ajax_content_example/simple', $response->getAttachments()['library']);
  }

}
