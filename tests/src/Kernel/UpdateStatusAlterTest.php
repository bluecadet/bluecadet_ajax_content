<?php

namespace Drupal\Tests\bluecadet_ajax_content\Kernel;

use Drupal\KernelTests\KernelTestBase;

/**
 * Tests update status alter behavior.
 *
 * @group bluecadet_ajax_content
 */
class UpdateStatusAlterTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['system', 'bluecadet_ajax_content'];

  /**
   * Tests projects that are not targeted remain unchanged.
   */
  public function testNonTargetProjectUnchanged(): void {
    $projects = [
      'example_module' => [
        'name' => 'example_module',
        'project_type' => 'module',
        'status' => 1,
      ],
    ];

    $expected = $projects;

    bluecadet_ajax_content_update_status_alter($projects);

    $this->assertSame($expected, $projects);
  }

  /**
   * Tests targeted module key is preserved after alteration.
   */
  public function testTargetProjectKeyPreserved(): void {
    $projects = [
      'bluecadet_ajax_content' => [
        'name' => 'bluecadet_ajax_content',
        'project_type' => 'module',
        'status' => 1,
      ],
    ];

    bluecadet_ajax_content_update_status_alter($projects);

    $this->assertArrayHasKey('bluecadet_ajax_content', $projects);
    $this->assertIsArray($projects['bluecadet_ajax_content']);
  }

}
