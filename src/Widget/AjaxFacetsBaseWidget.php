<?php

namespace Drupal\ajax_facets\Widget;

use Drupal\facets\FacetInterface;
use Drupal\facets\Result\Result;
use Drupal\facets\Result\ResultInterface;
use Drupal\facets\Widget\WidgetPluginBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * A base class for ajax widgets that implements most of the boilerplate.
 */
abstract class AjaxFacetsBaseWidget extends WidgetPluginBase {

  /**
   * {@inheritdoc}
   */
  public function build(FacetInterface $facet) {
    $build = parent::build($facet);
    
    $build['#attributes']['class'][] = 'js-facets-ajax';
    $build['#attached']['library'][] = 'ajax_facets/ajax_facets.ajax_facets_base';

    $source_id = $facet->getFacetSourceId();
    $source_data = explode(':', $source_id);
    
    $facet_id = $facet->id();
    

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
    $facet_item_id = $this->facet->getUrlAlias() . '-' . $result->getRawValue();
    $classes = ['facet-item', 'js-facet-item-' . $facet_item_id];
    
    if ($children = $result->getChildren()) {
      $items = $this->prepareLink($result, $facet);

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
      $items = $this->prepareLink($result, $facet);

      if ($result->isActive()) {
        $items['#attributes'] = ['class' => 'is-active'];
      }
    }

    $items['#attributes']['data-facet-query'] = $this->facet->getUrlAlias() . ':' . $result->getRawValue();
    $items['#wrapper_attributes'] = ['class' => $classes];
    $items['#attributes']['data-drupal-facet-item-id'] = $facet_item_id;

    return $items;
  }
  
  /**
   * Returns the text or link for an item.
   *
   * @param \Drupal\facets\Result\ResultInterface $result
   *   A result item.
   *
   * @return array
   *   The item as a render array.
   */
  protected function prepareLink(ResultInterface $result, FacetInterface $facet) {
    $item = $this->buildResultItem($result);

    if (!is_null($result->getUrl())) {
      $item = [
        '#type' => 'link',
        '#url' => Url::fromRoute('ajax_facets.ajax', ['facet_id' => $this->facet->id(), 'filter' => $this->facet->getUrlAlias() . ':' . $result->getRawValue()]),
        '#title' => $item,
        '#attributes' => ['class' => 'use-ajax'],
      ];
    }

    return $item;
  }

}

