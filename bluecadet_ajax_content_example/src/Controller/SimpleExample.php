<?php

namespace Drupal\bluecadet_ajax_content_example\Controller;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Cache\CacheableResponse;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\RendererInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * An example controller.
 */
class SimpleExample extends ControllerBase {

  /**
   * The renderer service.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * Constructs a SimpleExample controller.
   *
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *   The renderer service.
   */
  public function __construct(RendererInterface $renderer) {
    $this->renderer = $renderer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('renderer')
    );
  }

  /**
   * Builds the example page.
   */
  public function build(Request $request) {

    return [
      'center-content' => [
        '#markup' => '<div>this is crazy.</div><div data-ajax-now="/ajax-api/simple-example">...This will get replaced...</div><div>This is after the ajaxed content.</div>',
        '#attached' => [
          'library' => [
            'bluecadet_ajax_content/content-ajaxing',
          ],
        ],
      ],
    ];
  }

  /**
   * Returns the AJAX response used to replace the placeholder markup.
   */
  public function ajaxResponse(Request $request) {
    $build = [
      '#markup' => '<p>Ajaxed Paragraph 1.</p><p>Ajaxed Paragraph 2.</p><p>Ajaxed Paragraph 3.</p>',
    ];

    $response = new CacheableResponse('', 200);
    $output = (string) $this->renderer->renderRoot($build);

    $response->setContent($output);
    $cache_metadata = CacheableMetadata::createFromRenderArray($build);
    $response->addCacheableDependency($cache_metadata);

    if (isset($build['#content_type'])) {
      $response->headers->set('Content-type', $build['#content_type']);
    }
    else {
      $response->headers->set('Content-type', 'text/html; charset=utf-8');
    }

    return $response;
  }

}
