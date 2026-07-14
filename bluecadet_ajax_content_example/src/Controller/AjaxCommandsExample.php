<?php

namespace Drupal\bluecadet_ajax_content_example\Controller;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\RendererInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;



/**
 * An example controller.
 */
class AjaxCommandsExample extends ControllerBase {

  /**
   * The renderer service.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * Constructs an AjaxCommandsExample controller.
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

  public function build(Request $request) {

    return [
      'center-content' => [
        '#markup' => '<div>Etiam porta sem malesuada magna mollis euismod. Etiam porta sem malesuada magna mollis euismod. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas faucibus mollis interdum. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Praesent commodo cursus magna, vel scelerisque nisl consectetur et.</div>
          <div>Etiam porta sem malesuada magna mollis euismod. Etiam porta sem malesuada magna mollis euismod. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas faucibus mollis interdum. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Praesent commodo cursus magna, vel scelerisque nisl consectetur et.</div>
          <div class="simple-example">Etiam porta sem malesuada magna mollis euismod. Etiam porta sem malesuada magna mollis euismod. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas faucibus mollis interdum. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Praesent commodo cursus magna, vel scelerisque nisl consectetur et.</div>
          <div>Etiam porta sem malesuada magna mollis euismod. Etiam porta sem malesuada magna mollis euismod. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas faucibus mollis interdum. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Praesent commodo cursus magna, vel scelerisque nisl consectetur et.</div>
          <div>Etiam porta sem malesuada magna mollis euismod. Etiam porta sem malesuada magna mollis euismod. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas faucibus mollis interdum. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Praesent commodo cursus magna, vel scelerisque nisl consectetur et.</div>

          <div id="to-be-replaced-1" data-ajax-commands="/ajax-api/ajax-commands-example">...This will get replaced...</div>

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
      '#markup' => '<p>Ajaxed Paragraph 1.</p><p class="simple-example">Ajaxed Paragraph 2.</p><p>Ajaxed Paragraph 3.</p>',
      '#attached' => [
        'library' => [
          'bluecadet_ajax_content_example/simple',
        ],
      ],
    ];

    $output = (string) $this->renderer->renderRoot($build);

    $response = new AjaxResponse();
    $response->addCommand(new ReplaceCommand("#to-be-replaced-1", $output));

    $response->setAttachments($build['#attached']);

    return $response;

  }

}
