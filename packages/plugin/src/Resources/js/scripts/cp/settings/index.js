!function(){function e(t){return e="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},e(t)}$((function(){var t=$("#session-time"),n=$("#session-count"),a=$("#session-secret");$("select#session-context").on({change:function(){switch($(this).val()){case"payload":a.removeClass("hidden"),n.addClass("hidden"),t.addClass("hidden");break;case"session":case"database":a.addClass("hidden"),n.removeClass("hidden"),t.removeClass("hidden")}}}),$("input[name='purge-toggle']").parents(".lightswitch").on({change:function(){$("input",this).val()||$("select#purge-value").val(0)}}),$("select#spam-protection-behavior").on({change:function(){var e=$("#custom-spam-error-message");"display_errors"===$(this).val()?e.show("fast"):e.hide("fast")}});var i=$('select[name="settings[scriptInsertLocation]"]'),s=$("#script-insert-warning").text();i.on({change:function(){var e=$(this).val(),t=$(this).parents(".field:first");if("manual"===e){var n=document.createElement("div");n.classList.add("warning","with-icon"),n.innerText=s,console.log(t,n),t.append(n)}else t.find(".warning.with-icon").remove()}}),i.trigger("change");var o=$("#files-directory"),r=$("#template-default");$("#storage-type").on({change:function(e){var t=e.target.value;["files","files_database"].includes(t)?o.removeClass("hidden"):o.addClass("hidden"),"files_database"===t?r.removeClass("hidden"):r.addClass("hidden")}});var c=$("#notifications-migrator");c&&$("#migrate",c).on({click:function(t){if(!confirm("Are you sure you want to migrate database notifications to file based ones?"))return t.preventDefault(),t.stopPropagation(),!1;var n,a,i,s=$("#remove-files",c).is(":checked");return $.ajax({url:Craft.getCpUrl("freeform/migrate/notifications/db-to-file"),type:"post",dataType:"json",contentType:"application/json",data:JSON.stringify((n={removeDbNotifications:s},a=Craft.csrfTokenName,i=Craft.csrfTokenValue,(a=function(t){var n=function(t,n){if("object"!==e(t)||null===t)return t;var a=t[Symbol.toPrimitive];if(void 0!==a){var i=a.call(t,"string");if("object"!==e(i))return i;throw new TypeError("@@toPrimitive must return a primitive value.")}return String(t)}(t);return"symbol"===e(n)?n:String(n)}(a))in n?Object.defineProperty(n,a,{value:i,enumerable:!0,configurable:!0,writable:!0}):n[a]=i,n)),success:function(e){e.success&&c.html($('<div class="pane">\n                  <p>\n                    <span class="checkmark-icon"></span>\n                    Migrated successfully\n                  </p> \n                </div>\n                '))}}),t.preventDefault(),t.stopPropagation(),!1}}),$(".lock-button").on("click",(function(){var e=$("input",this);e.val("1"===e.val()?"0":"1"),e.toggleClass("locked","1"===e.val())}))}))}();