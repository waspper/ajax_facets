<?php

/**
 * @file
 * Definition of Drupal\ajax_facets\Ajax\AjaxFacetsReloadCommand.
 */

namespace Drupal\ajax_facets\Ajax;

use Drupal\Core\Ajax\CommandInterface;

class AjaxFacetsReloadCommand implements CommandInterface {

  /**
   * Implements Drupal\Core\Ajax\CommandInterface:render().
   */
  public function render() {
    return array(
      'command' => 'AjaxFacetsReload',
    );
  }

}
