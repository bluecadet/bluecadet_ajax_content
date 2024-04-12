<?php

namespace Drupal\bluecadet_ajax_content_example\Controller;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Cache\CacheableResponse;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * An example controller.
 */
class ScrollExample extends ControllerBase {

  public function build(Request $request) {

    return [
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

  public function ajaxResponse(Request $request) {
    $build = [
      '#markup' => '<p>Ajaxed Paragraph 1.</p><p>Ajaxed Paragraph 2.</p><p>Ajaxed Paragraph 3.</p>'
    ];

    $response = new CacheableResponse('', 200);
    $renderer = \Drupal::service('renderer');
    $output = (string) $renderer->renderRoot($build);

    $response->setContent($output);
    $cache_metadata = CacheableMetadata::createFromRenderArray($build);
    $response->addCacheableDependency($cache_metadata);

    if (isset($build['#content_type'])) {
      $response->headers->set('Content-type', $build['#content_type']);
    }
    else {
      $response->headers->set('Content-type', "text/html; charset=utf-8");
    }

    return $response;
  }

}
