webpackJsonp([3],{

/***/ "./node_modules/babel-loader/lib/index.js?{\"cacheDirectory\":true,\"presets\":[[\"env\",{\"modules\":false,\"targets\":{\"browsers\":[\"> 2%\"],\"uglify\":true}}],[\"env\"]],\"plugins\":[\"transform-object-rest-spread\",[\"transform-runtime\",{\"polyfill\":false,\"helpers\":false}],\"transform-runtime\",\"transform-vue-jsx\",\"syntax-jsx\",\"transform-object-rest-spread\"],\"env\":{\"test\":{\"presets\":[[\"env\",{\"targets\":{\"node\":\"current\"}}]]}}}!./node_modules/vue-loader/lib/selector.js?type=script&index=0!./packages/wqa/nova-sortable-toggle-fields/resources/js/components/SortableToggleFields.vue":
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _laravelNova = __webpack_require__("./node_modules/laravel-nova/dist/index.js");

var _vuedraggable = __webpack_require__(!(function webpackMissingModule() { var e = new Error("Cannot find module \"vuedraggable\""); e.code = 'MODULE_NOT_FOUND'; throw e; }()));

var _vuedraggable2 = _interopRequireDefault(_vuedraggable);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

exports.default = {
  mixins: [_laravelNova.InteractsWithResourceInformation],

  components: {
    draggable: _vuedraggable2.default
  },

  props: ['viaResource', 'viaResourceId', 'resourceName', 'field', 'editMode'],

  data: function data() {
    return {};
  },

  methods: {
    toggleIcon: function toggleIcon(item) {
      if (this.editMode) {
        item.active = !item.active;
        this.onDraggableUpdate();
      }
    },
    onDraggableUpdate: function onDraggableUpdate() {
      var resourceId = this.$refs.toggleFields.$parent.$parent.$el.attributes['data-id'].value;
      Nova.bus.$emit('onToggleFields_Resource' + resourceId, [this.field.value, this.field.attribute]);
    }
  }
};

/***/ }),

/***/ "./node_modules/vue-loader/lib/component-normalizer.js":
/***/ (function(module, exports) {

/* globals __VUE_SSR_CONTEXT__ */

// IMPORTANT: Do NOT use ES2015 features in this file.
// This module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle.

module.exports = function normalizeComponent (
  rawScriptExports,
  compiledTemplate,
  functionalTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier /* server only */
) {
  var esModule
  var scriptExports = rawScriptExports = rawScriptExports || {}

  // ES6 modules interop
  var type = typeof rawScriptExports.default
  if (type === 'object' || type === 'function') {
    esModule = rawScriptExports
    scriptExports = rawScriptExports.default
  }

  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (compiledTemplate) {
    options.render = compiledTemplate.render
    options.staticRenderFns = compiledTemplate.staticRenderFns
    options._compiled = true
  }

  // functional template
  if (functionalTemplate) {
    options.functional = true
  }

  // scopedId
  if (scopeId) {
    options._scopeId = scopeId
  }

  var hook
  if (moduleIdentifier) { // server build
    hook = function (context) {
      // 2.3 injection
      context =
        context || // cached call
        (this.$vnode && this.$vnode.ssrContext) || // stateful
        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional
      // 2.2 with runInNewContext: true
      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {
        context = __VUE_SSR_CONTEXT__
      }
      // inject component styles
      if (injectStyles) {
        injectStyles.call(this, context)
      }
      // register component module identifier for async chunk inferrence
      if (context && context._registeredComponents) {
        context._registeredComponents.add(moduleIdentifier)
      }
    }
    // used by ssr in case component is cached and beforeCreate
    // never gets called
    options._ssrRegister = hook
  } else if (injectStyles) {
    hook = injectStyles
  }

  if (hook) {
    var functional = options.functional
    var existing = functional
      ? options.render
      : options.beforeCreate

    if (!functional) {
      // inject component registration as beforeCreate hook
      options.beforeCreate = existing
        ? [].concat(existing, hook)
        : [hook]
    } else {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functioal component in vue file
      options.render = function renderWithStyleInjection (h, context) {
        hook.call(context)
        return existing(h, context)
      }
    }
  }

  return {
    esModule: esModule,
    exports: scriptExports,
    options: options
  }
}


/***/ }),

/***/ "./node_modules/vue-loader/lib/template-compiler/index.js?{\"id\":\"data-v-0c6e56c2\",\"hasScoped\":false,\"buble\":{\"transforms\":{}}}!./node_modules/vue-loader/lib/selector.js?type=template&index=0!./packages/wqa/nova-sortable-toggle-fields/resources/js/components/SortableToggleFields.vue":
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    { staticClass: "drag inline-flex" },
    [
      _c(
        "draggable",
        {
          ref: "toggleFields",
          staticClass: "dragArea list-reset flex",
          class: "list_" + _vm.viaResourceId,
          attrs: {
            element: "ul",
            options: { draggable: ".item", disabled: !_vm.editMode },
            list: _vm.field.value
          },
          on: { update: _vm.onDraggableUpdate }
        },
        _vm._l(_vm.field.value, function(icon) {
          return icon.name
            ? _c(
                "li",
                {
                  staticClass: "mr-1 ml-1 item",
                  class: { active: icon.active },
                  attrs: { "data-id": icon.active ? icon.name : 0 }
                },
                [
                  _c("img", {
                    attrs: { src: "/icons/" + icon.name + ".svg" },
                    on: {
                      click: function($event) {
                        _vm.toggleIcon(icon)
                      }
                    }
                  })
                ]
              )
            : _vm._e()
        })
      )
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-0c6e56c2", module.exports)
  }
}

/***/ }),

/***/ "./packages/wqa/nova-sortable-toggle-fields/resources/js/components/SortableToggleFields.vue":
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__("./node_modules/vue-loader/lib/component-normalizer.js")
/* script */
var __vue_script__ = __webpack_require__("./node_modules/babel-loader/lib/index.js?{\"cacheDirectory\":true,\"presets\":[[\"env\",{\"modules\":false,\"targets\":{\"browsers\":[\"> 2%\"],\"uglify\":true}}],[\"env\"]],\"plugins\":[\"transform-object-rest-spread\",[\"transform-runtime\",{\"polyfill\":false,\"helpers\":false}],\"transform-runtime\",\"transform-vue-jsx\",\"syntax-jsx\",\"transform-object-rest-spread\"],\"env\":{\"test\":{\"presets\":[[\"env\",{\"targets\":{\"node\":\"current\"}}]]}}}!./node_modules/vue-loader/lib/selector.js?type=script&index=0!./packages/wqa/nova-sortable-toggle-fields/resources/js/components/SortableToggleFields.vue")
/* template */
var __vue_template__ = __webpack_require__("./node_modules/vue-loader/lib/template-compiler/index.js?{\"id\":\"data-v-0c6e56c2\",\"hasScoped\":false,\"buble\":{\"transforms\":{}}}!./node_modules/vue-loader/lib/selector.js?type=template&index=0!./packages/wqa/nova-sortable-toggle-fields/resources/js/components/SortableToggleFields.vue")
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "packages/wqa/nova-sortable-toggle-fields/resources/js/components/SortableToggleFields.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-0c6e56c2", Component.options)
  } else {
    hotAPI.reload("data-v-0c6e56c2", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),

/***/ "./packages/wqa/nova-sortable-toggle-fields/resources/js/nova-sortable-toggle-fields.js":
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Nova.booting(function (Vue, router) {
    Vue.config.devtools = true;
    Vue.component('index-nova-sortable-toggle-fields', __webpack_require__("./packages/wqa/nova-sortable-toggle-fields/resources/js/components/SortableToggleFields.vue"));
});

/***/ }),

/***/ 2:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__("./packages/wqa/nova-sortable-toggle-fields/resources/js/nova-sortable-toggle-fields.js");


/***/ })

},[2]);