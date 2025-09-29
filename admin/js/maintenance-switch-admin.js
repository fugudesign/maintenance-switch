(function ($) {
  "use strict";

  $(document).ready(function () {
    var editor = wp.codeEditor.initialize("ms_page_html");
    // Update the hidden textarea on codemirror change
    editor.codemirror.on("change", function (CodeMirror) {
      CodeMirror.save();
    });

    // Initialize tabs with active tab persistence
    var tabs = $("#settings-tabs").tabs();
    
    // Get active tab from various sources (priority order)
    var activeTabIndex = 0;
    var urlHash = window.location.hash;
    var phpActiveTab = typeof maintenance_switch_admin !== 'undefined' ? maintenance_switch_admin.active_tab : null;
    var wasSubmitted = typeof maintenance_switch_admin !== 'undefined' ? maintenance_switch_admin.submitted : 0;
    var savedTab = localStorage.getItem('ms_active_tab');
    
    // Priority: PHP post data > URL hash > localStorage > default
    if (wasSubmitted && phpActiveTab !== null) {
      activeTabIndex = parseInt(phpActiveTab) || 0;
    } else if (urlHash) {
      // Find tab index by hash
      $("#settings-tabs ul li a").each(function(index) {
        if ($(this).attr('href') === urlHash) {
          activeTabIndex = index;
          return false;
        }
      });
    } else if (savedTab !== null) {
      activeTabIndex = parseInt(savedTab) || 0;
    }
    
    // Activate the correct tab
    tabs.tabs("option", "active", activeTabIndex);
    
    // Save active tab on change
    tabs.on("tabsactivate", function(event, ui) {
      var activeIndex = ui.newTab.index();
      localStorage.setItem('ms_active_tab', activeIndex);
      // Update URL hash without triggering page refresh
      var tabId = ui.newPanel.attr('id');
      history.replaceState(null, null, '#' + tabId);
    });
    
    // Intercept form submissions to maintain active tab
    $('form#settings-form').on('submit', function() {
      var activeTab = tabs.tabs("option", "active");
      localStorage.setItem('ms_active_tab', activeTab);
      
      // Remove existing active_tab input to avoid duplicates
      $(this).find('input[name="active_tab"]').remove();
      
      // Add tab parameter as hidden input (will be caught by redirect hook)
      var tabInput = $('<input>').attr({
        type: 'hidden',
        name: 'active_tab',
        value: activeTab
      });
      $(this).append(tabInput);
    });

    $("#addmyip").on("click", function (e) {
      e.preventDefault();

      var ip = $(this).data("ip");

      var ipRegex = /\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/;
      var valid = ipRegex.test(ip);

      if (valid == true) {
        var ips = $("#ms_allowed_ips").val();
        var new_ips = ips != "" ? ips + ", " + ip : ip;
        $("#ms_allowed_ips").val(new_ips);
      }
    });

    function toggleTextareaReadonly(status) {
      var checked = status || $("#ms_use_theme").prop("checked");
      $("#ms_page_html").prop("readonly", checked);
      $("#ms_page_html").next(".CodeMirror").toggleClass("readonly", checked);
    }
    toggleTextareaReadonly();

    $("#ms_use_theme").on("change", function (e) {
      var checked = this.checked;
      toggleTextareaReadonly(checked);
    });

    $("#page-preview").on("click", function (e) {
      e.preventDefault();
      
      var form = $("#preview-form");
      var theme = $("#ms_use_theme").prop("checked");
      
      if (theme) {
        var url = $("#ms_preview_theme_file").val();
        form.attr("action", url).submit();
      } else {
        var html = $("#ms_page_html").val();
        
        // Clear only the preview-code input if it exists, preserve nonce
        form.find("input[name='preview-code']").remove();
        form.append(
          $("<input/>").attr({
            type: "hidden",
            id: "preview-code",
            name: "preview-code",
            value: html,
          })
        );
        
        var defaultAction = form.data("default-action");
        form.attr("action", defaultAction);
        
        // Ensure new window opens - force target behavior
        var newWindow = window.open("", "ms-preview");
        form.attr("target", "ms-preview");
        form.submit();
      }
    });

    $("input[data-msg]").on("click", function (e) {
      var message = $(this).data("msg");
      if (!confirm(message)) e.preventDefault();
    });
  });
})(jQuery);
