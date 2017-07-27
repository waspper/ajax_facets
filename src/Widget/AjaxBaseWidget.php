<?php

namespace Drupal\ajax_facets\Widget;

use Drupal\facets\FacetInterface;
use Drupal\facets\Result\Result;
use Drupal\facets\Result\ResultInterface;
use Drupal\facets\Widget\WidgetPluginBase;

/**
 * A base class for ajax widgets that implements most of the boilerplate.
 */
abstract class AjaxBaseWidget extends WidgetPluginBase {

  /**
   * {@inheritdoc}
   */
  public function build(FacetInterface $facet) {
    $build = parent::build($facet);
    
    $build['#attributes']['class'][] = 'js-facets-ajax';
    $build['#attached']['library'][] = 'ajax_facets/ajax_facets.ajax_facets_base';

    $source_id = $facet->getFacetSourceId();
    $source_data = explode(':', $source_id);

    $build['#attributes']['class'][] = 'js-facets-ajax-' . $source_data[1];
    $build['#attached']['drupalSettings'] = [
      'ajaxFacets' => [
        'view_name' => $source_data[1],
      ],
    ];

    return $build;
  }

  /**
   * Builds a renderable array of result items.
   *
   * @param \Drupal\facets\FacetInterface $facet
   *   The facet we need to build.
   * @param \Drupal\facets\Result\ResultInterface $result
   *   A result item.
   *
   * @return array
   *   A renderable array of the result.
   */
  protected function buildListItems($facet, ResultInterface $result) {
    $classes = ['facet-item'];

    if ($children = $result->getChildren()) {
      $items = $this->prepareLink($result);

      $children_markup = [];
      foreach ($children as $child) {
        $children_markup[] = $this->buildChild($child);
      }

      $classes[] = 'expanded';
      $items['children'] = [$children_markup];

      if ($result->isActive()) {
        $items['#attributes'] = ['class' => 'active-trail'];
      }
    }
    else {
      $items = $this->prepareLink($result);

      if ($result->isActive()) {
        $items['#attributes'] = ['class' => 'is-active'];
      }
    }

    $items['#attributes']['data-facet-query'] = $this->facet->getUrlAlias() . ':' . $result->getRawValue();
    $items['#wrapper_attributes'] = ['class' => $classes];
    $items['#attributes']['data-drupal-facet-item-id'] = $this->facet->getUrlAlias() . '-' . $result->getRawValue();

    return $items;
  }

}
