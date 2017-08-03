/**
 * @file
 * Handles AJAX fetching of views, including filter submission and response.
 */

(function ($, Drupal, drupalSettings) {

  'use strict';

  Drupal.ajax_facets = Drupal.ajax_facets || {viewDomId: null, viewSettings: null};
  Drupal.ajax_facets_filters = Drupal.ajax_facets_filters || [];
  Drupal.behaviors.AjaxFacetsWidget = {
    attach: function (context, drupalSettings) {
      Drupal.ajax_facets.attach(context, drupalSettings);
      Drupal.AjaxCommands.prototype.AjaxFacetsReload = function(ajax, response, status) {
        if (drupalSettings.ajaxFacets.filter) {
          if ($.inArray(drupalSettings.ajaxFacets.filter, Drupal.ajax_facets_filters) !== -1) {
            // Remove previously added filter.
            Drupal.ajax_facets_filters = $(Drupal.ajax_facets_filters).not([drupalSettings.ajaxFacets.filter]).get();
          }
          else {
            // Append selected filter.
            Drupal.ajax_facets_filters.push(drupalSettings.ajaxFacets.filter);  
          }
        }
        Drupal.ajax_facets.ajaxView();
      };
    }
  };

  /**
   * Trying to find DomID for our view.
   */
  Drupal.ajax_facets.attach = function (context, drupalSettings) {
    $.each(drupalSettings.views.ajaxViews, function (key, value) {
      // Newer versions of Facets include the view type. Ex.: views_page__MY-VIEW__page_N
      if (~drupalSettings.ajaxFacets.view_name.indexOf(value.view_name)) {
        Drupal.ajax_facets.viewDomId = key;
        Drupal.ajax_facets.viewSettings = value;
      }
    });
  };

  /**
   * Javascript object for a certain view.
   *
   * @constructor
   *
   * @param {object} settings
   *   Settings object for the ajax view.
   * @param {array} facet_query
   *   The query parameters.
   */
  //Drupal.ajax_facets.ajaxView = function (settings, facet_query) {
  Drupal.ajax_facets.ajaxView = function () {
    var settings = Drupal.ajax_facets.viewSettings;
    var selector = '.js-view-dom-id-' + settings.view_dom_id;
    this.$view = $(selector);
    // Retrieve the path to use for views' ajax.
    var ajax_path = drupalSettings.views.ajax_path;

    // If there are multiple views this might've ended up showing up multiple
    // times.
    if (ajax_path.constructor.toString().indexOf('Array') !== -1) {
      ajax_path = ajax_path[0];
    }

    // Check if there are any GET parameters to send to views.
    var queryString = window.location.search || '';
    if (queryString !== '') {
      // Remove the question mark and Drupal path component if any.
      queryString = queryString.slice(1).replace(/q=[^&]+&?|&?render=[^&]+/, '');
      if (queryString !== '') {
        // If there is a '?' in ajax_path, clean url are on and & should be
        // used to add parameters.
        queryString = ((/\?/.test(ajax_path)) ? '&' : '?') + queryString;
      }
    }

    // Add facets to query.
    //settings.f = facet_query;
    settings.f = Drupal.ajax_facets_filters;

    this.element_settings = {
      url: ajax_path + queryString,
      submit: settings,
      setClick: true,
      event: 'click',
      selector: selector,
      progress: {type: 'fullscreen'}
    };

    this.settings = settings;

    // Add the ajax to exposed forms.
    this.$exposed_form = $('form#views-exposed-form-' + settings.view_name.replace(/_/g, '-') + '-' + settings.view_display_id.replace(/_/g, '-'));
    this.$exposed_form.once('exposed-form').each($.proxy(this.attachExposedFormAjax, this));

    // Add the ajax to pagers.
    this.$view
      // Don't attach to nested views. Doing so would attach multiple behaviors
      // to a given element.
      .filter($.proxy(this.filterNestedViews, this))
      .once('ajax-pager').each($.proxy(this.attachPagerAjax, this));

    // Add a trigger to update this view specifically. In order to trigger a
    // refresh use the following code.
    //
    // @code
    // $('.view-name').trigger('RefreshView');
    // @endcode
    var self_settings = $.extend({}, this.element_settings, {
      event: 'RefreshView',
      base: this.selector,
      element: this.$view.get(0)
    });
    this.refreshViewAjax = Drupal.ajax(self_settings);
    this.refreshViewAjax.execute();
  };

})(jQuery, Drupal, drupalSettings);
 
