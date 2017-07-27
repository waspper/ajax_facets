/**
 * @file
 * Converts Facets options as AJAX checkboxes.
 */

(function ($, Drupal, drupalSettings) {

  'use strict';

  Drupal.ajax_facets_links = Drupal.ajax_facets_links || { viewDomId: null, viewSettings: null };
  Drupal.behaviors.facetsAjaxLinksWidget = {
    attach: function (context, drupalSettings) {
      Drupal.ajax_facets_links.makeAjaxLinksFacet(context, drupalSettings);
    }
  };

  /**
   * Replace a link with a checked checkbox.
   * 
   * @param {object} context
   *   Context.
   * @param {object} settings
   *   Settings.
   */
  Drupal.ajax_facets_links.makeAjaxLinksFacet = function (context, settings) {
    $('.js-facets-ajax-links').once('facets-links-ajaxify').each(function () {
      var $ul = $(this);
      var $links = $ul.find('.facet-item a');
      $links.each(function () {
        var $link = $(this);
        $link.on('click', function (event) {
          $(this).toggleClass('active');
          var facet_query = [];
          $('.js-facets-ajax-links .active').each(function(){
            facet_query.push($(this).attr('data-facet-query'));
          });
          Drupal.ajax_facets.ajaxView(null, facet_query);
          event.preventDefault();
        });
      });
    });
  }

})(jQuery, Drupal, drupalSettings);
 
 
