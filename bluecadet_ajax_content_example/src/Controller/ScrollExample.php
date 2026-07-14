<?php

namespace Drupal\bluecadet_ajax_content_example\Controller;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Cache\CacheableResponse;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Render\RendererInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * An example controller.
 */
class ScrollExample extends ControllerBase {

  /**
   * The renderer service.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * Constructs a ScrollExample controller.
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
      'example-nav' => [
        '#theme' => 'item_list',
        '#items' => [
          Link::createFromRoute($this->t('Simple Example'), 'bluecadet_ajax_content_example.simple_example_immediate'),
          Link::createFromRoute($this->t('Scroll Example'), 'bluecadet_ajax_content_example.simple_example_scroll'),
          Link::createFromRoute($this->t('Ajax Commands Example'), 'bluecadet_ajax_content_example.ajax_commands_example_scroll'),
        ],
      ],
      'center-content' => [
        '#markup' => '<div>Etiam porta sem malesuada magna mollis euismod. Etiam porta sem malesuada magna mollis euismod. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas faucibus mollis interdum. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Praesent commodo cursus magna, vel scelerisque nisl consectetur et.</div>
        <div>Etiam porta sem malesuada magna mollis euismod. Etiam porta sem malesuada magna mollis euismod. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas faucibus mollis interdum. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Praesent commodo cursus magna, vel scelerisque nisl consectetur et.</div>
        <div>Etiam porta sem malesuada magna mollis euismod. Etiam porta sem malesuada magna mollis euismod. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas faucibus mollis interdum. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Praesent commodo cursus magna, vel scelerisque nisl consectetur et.</div>
        <div>Etiam porta sem malesuada magna mollis euismod. Etiam porta sem malesuada magna mollis euismod. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas faucibus mollis interdum. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Praesent commodo cursus magna, vel scelerisque nisl consectetur et.</div>
        <div>Etiam porta sem malesuada magna mollis euismod. Etiam porta sem malesuada magna mollis euismod. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas faucibus mollis interdum. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Praesent commodo cursus magna, vel scelerisque nisl consectetur et.</div>

        <div data-ajax-scroll="/ajax-api/scroll-example">...This will get replaced...</div>

        <div>Etiam porta sem malesuada magna mollis euismod. Etiam porta sem malesuada magna mollis euismod. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas faucibus mollis interdum. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Praesent commodo cursus magna, vel scelerisque nisl consectetur et.</div>
        ',
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
