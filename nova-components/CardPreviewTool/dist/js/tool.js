/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
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
/* 1 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(2);
module.exports = __webpack_require__(9);


/***/ }),
/* 2 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Nova.booting(function (Vue, router, store) {
    Vue.component('card-preview-tool', __webpack_require__(3));
    Vue.component('card-preview', __webpack_require__(6));
});

/***/ }),
/* 3 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(4)
/* template */
var __vue_template__ = __webpack_require__(5)
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
Component.options.__file = "resources/js/components/Tool.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-68ff5483", Component.options)
  } else {
    hotAPI.reload("data-v-68ff5483", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 4 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});
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
//
//
//
//
//

exports.default = {
    props: ['resourceName', 'resourceId', 'field'],

    data: function data() {
        return {
            isLoading: true,
            card: {}
        };
    },
    mounted: function mounted() {
        this.updatePreview();
    },


    methods: {
        updatePreview: function updatePreview() {
            var _this = this;

            this.isLoading = true;

            Nova.request().get('/nova-vendor/card-preview-tool/' + this.resourceId).then(function (response) {
                _this.card = response.data.data;
                _this.isLoading = false;
            });
        }
    }
};

/***/ }),
/* 5 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", { staticClass: "card-preview-tool" }, [
    _c("p", { staticClass: "mb-2" }, [
      _vm._v(
        "Below you will find a representation of what the card will look like in the app."
      )
    ]),
    _vm._v(" "),
    _c("p", { staticClass: "mb-8" }, [
      _vm._v(
        'In order to see the latest preview, make sure you save the card and click on "Refresh preview".'
      )
    ]),
    _vm._v(" "),
    _c("button", {
      staticClass: "mb-8 btn btn-default btn-primary",
      domProps: {
        textContent: _vm._s(
          _vm.isLoading ? "Updating preview..." : "Refresh preview"
        )
      },
      on: {
        click: function($event) {
          return _vm.updatePreview()
        }
      }
    }),
    _vm._v(" "),
    _c("div", { staticClass: "flex" }, [
      _c(
        "div",
        { staticClass: "mr-6" },
        [
          _c("h4", { staticClass: "mb-2" }, [_vm._v("iPhone 5s, SE")]),
          _vm._v(" "),
          _c("card-preview", { attrs: { card: _vm.card, width: 270 } })
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "div",
        { staticClass: "mr-6" },
        [
          _c("h4", { staticClass: "mb-2" }, [_vm._v("iPhone 6, 7, 8, X")]),
          _vm._v(" "),
          _c("card-preview", { attrs: { card: _vm.card, width: 325 } })
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "div",
        [
          _c("h4", { staticClass: "mb-2" }, [_vm._v("iPhone XR, XS Max")]),
          _vm._v(" "),
          _c("card-preview", { attrs: { card: _vm.card, width: 364 } })
        ],
        1
      )
    ])
  ])
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-68ff5483", module.exports)
  }
}

/***/ }),
/* 6 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(7)
/* template */
var __vue_template__ = __webpack_require__(8)
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
Component.options.__file = "resources/js/components/CardPreview.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-e4a14506", Component.options)
  } else {
    hotAPI.reload("data-v-e4a14506", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 7 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});
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
    props: ['card', 'width'],

    methods: {
        formattedDescription: function formattedDescription() {
            return this.card.description.replace(/\n/g, '<br>');
        },
        formattedTitle: function formattedTitle() {
            return this.card.title.replace(/\n/g, '<br>');
        }
    },

    computed: {
        cardStyles: function cardStyles() {
            return {
                'backgroundColor': this.card.background_color ? '#' + this.card.background_color : 'transparent',
                'backgroundImage': this.card.image_url ? 'url(\'' + this.card.image_url + '\')' : '',
                'width': this.width + 'px'
            };
        },
        cardClasses: function cardClasses() {
            return {
                'el-card--text-color-dark': this.card.text_style === "Dark",
                'el-card--text-color-light': this.card.text_style === "Light",

                'el-card--overlay-dark': this.card.overlay_style === "Dark",
                'el-card--overlay-light': this.card.overlay_style === "Light",

                'el-card--content-vertical-alignment-top': this.card.content_vertical_alignment === "Top",
                'el-card--content-vertical-alignment-center': this.card.content_vertical_alignment === "Center",
                'el-card--content-vertical-alignment-bottom': this.card.content_vertical_alignment === "Bottom",

                'el-card--content-horizontal-alignment-left': this.card.content_horizontal_alignment === "Left",
                'el-card--content-horizontal-alignment-center': this.card.content_horizontal_alignment === "Center",
                'el-card--content-horizontal-alignment-right': this.card.content_horizontal_alignment === "Right"
            };
        }
    }
};

/***/ }),
/* 8 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    { staticClass: "el-card", class: _vm.cardClasses, style: _vm.cardStyles },
    [
      _c("div", { staticClass: "el-card__inner" }, [
        _vm.card.header_image_url
          ? _c("img", {
              staticClass: "el-card__header-image",
              attrs: { src: _vm.card.header_image_url }
            })
          : _vm._e(),
        _vm._v(" "),
        _vm.card.subtitle
          ? _c("p", { staticClass: "el-card__subtitle" }, [
              _vm._v(_vm._s(_vm.card.subtitle))
            ])
          : _vm._e(),
        _vm._v(" "),
        _vm.card.title
          ? _c("h1", {
              staticClass: "el-card__title",
              domProps: { innerHTML: _vm._s(_vm.formattedTitle()) }
            })
          : _vm._e(),
        _vm._v(" "),
        _vm.card.description
          ? _c("p", {
              staticClass: "el-card__description",
              domProps: { innerHTML: _vm._s(_vm.formattedDescription()) }
            })
          : _vm._e(),
        _vm._v(" "),
        _vm.card.button_text
          ? _c("span", { staticClass: "el-card__button" }, [
              _vm._v(_vm._s(_vm.card.button_text))
            ])
          : _vm._e()
      ])
    ]
  )
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-e4a14506", module.exports)
  }
}

/***/ }),
/* 9 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ })
/******/ ]);