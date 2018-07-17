/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */

define([
    'jquery',
    'uiComponent',
    'Buzzi_Publish/js/action/save-event'
], function ($, Component, saveEvent) {
    "use strict";

    return Component.extend({
        defaults: {
            event_type: null,
            search_form: null,
            page_url: null
        },

        initialize: function () {
            this._super();

            if (this.search_form && this.page_url) {
                this.observeSearchForm();
            }
        },

        observeSearchForm: function () {
            var self = this;

            $('body').on('submit', "form#" + this.search_form, function() {
                var searchQuery = $.trim($(this).find('input[name=q]').val());
                if (!searchQuery) {
                    return;
                }

                saveEvent(
                    self.event_type,
                    {
                        page_url: self.page_url,
                        search_type: self.search_form,
                        search_query: searchQuery
                    },
                    searchQuery
                );
            });
        }
    });
});
