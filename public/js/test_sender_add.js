/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/module/component/modalOperation.js":
/*!*********************************************************!*\
  !*** ./resources/js/module/component/modalOperation.js ***!
  \*********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   close_image_edit_modal: () => (/* binding */ close_image_edit_modal),
/* harmony export */   close_loader: () => (/* binding */ close_loader),
/* harmony export */   close_loader_template: () => (/* binding */ close_loader_template),
/* harmony export */   close_modal: () => (/* binding */ close_modal),
/* harmony export */   close_modal_by_click: () => (/* binding */ close_modal_by_click),
/* harmony export */   hide_bg: () => (/* binding */ hide_bg),
/* harmony export */   open_image_edit_modal: () => (/* binding */ open_image_edit_modal),
/* harmony export */   open_loader: () => (/* binding */ open_loader),
/* harmony export */   open_loader_template: () => (/* binding */ open_loader_template),
/* harmony export */   open_modal: () => (/* binding */ open_modal)
/* harmony export */ });
/* harmony import */ var _ui_FormController_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ui/FormController.js */ "./resources/js/module/component/ui/FormController.js");

var open_modal = function open_modal(modal) {
  var loader = document.querySelector(".loader");
  document.querySelector(".bg").classList.remove("hidden");
  loader.classList.add("hidden");
  modal.classList.remove("hidden");
};
var close_modal = function close_modal() {
  var bg = document.querySelector(".bg");
  var modals = document.querySelectorAll(".js_modal");
  var alerts = document.querySelectorAll(".js_alert_danger");
  var loader = document.querySelector(".loader");
  var imageEditModal = document.getElementById("js_image_edit_modal");
  if (document.getElementById("js_cancel_btn")) {
    document.getElementById("js_cancel_btn").addEventListener("click", function () {
      document.getElementById("js_broadcast_confirm_modal").classList.add("hidden");
      document.querySelector(".broadcasting_message_modal").style.zIndex = "999";
    });
  }
  bg.addEventListener("click", function () {
    if (!loader.classList.contains("hidden")) {
      return;
    }
    if (imageEditModal.classList.contains("hidden") == false) {
      imageEditModal.classList.add("hidden");
      _ui_FormController_js__WEBPACK_IMPORTED_MODULE_0__["default"].initializeFileUpload();
      return;
    }
    if (document.querySelector(".broadcasting_message_modal").classList.contains("hidden") == false) {
      document.getElementById("js_broadcast_confirm_modal").classList.remove("hidden");
      document.querySelector(".broadcasting_message_modal").style.zIndex = "997";
      return;
    }

    // 通常処理
    bg.classList.add("hidden");
    loader.classList.add("hidden");
    modals.forEach(function (modal) {
      modal.classList.add("hidden");
    });
    if (alerts) {
      alerts.forEach(function (alert) {
        alert.classList.add("hidden");
      });
    }
  });
};
var open_loader = function open_loader() {
  var loader = document.querySelector(".loader");
  var bg = document.querySelector(".bg");
  bg.classList.remove("hidden");
  loader.classList.remove("hidden");
};
var close_loader = function close_loader() {
  var loader = document.querySelector(".loader");
  loader.classList.add("hidden");
};
var hide_bg = function hide_bg() {
  var bg = document.querySelector(".bg");
  bg.classList.add("hidden");
};
var close_modal_by_click = function close_modal_by_click(modal, btn) {
  var bg = document.querySelector(".bg");
  btn.addEventListener("click", function () {
    bg.classList.add("hidden");
    modal.classList.add("hidden");
  });
};
var open_loader_template = function open_loader_template() {
  var loader = document.querySelector(".loader");
  var bg = document.querySelector(".bg_temaplteModal");
  var modal = document.getElementById("js_template_modal");
  modal.style.zIndex = 998;
  loader.style.zIndex = 999;
  loader.classList.remove("hidden");
  bg.classList.remove("hidden");
};
var close_loader_template = function close_loader_template() {
  var loader = document.querySelector(".loader");
  var bg = document.querySelector(".bg_temaplteModal");
  var modal = document.getElementById("js_template_modal");
  modal.style.zIndex = 999;
  loader.style.zIndex = 998;
  loader.classList.add("hidden");
  bg.classList.add("hidden");
};
var open_image_edit_modal = function open_image_edit_modal() {
  var modal = document.querySelector(".image_edit_modal");
  var fixed_bg = document.querySelector(".fixed_bg");
  var loader = document.querySelector(".loader");
  loader.classList.add("hidden");
  modal.classList.remove("hidden");
  fixed_bg.classList.remove("hidden");
};
var close_image_edit_modal = function close_image_edit_modal(inputElement) {
  var fixed_bg = document.querySelector(".fixed_bg");
  var imageEditModal = document.querySelector(".image_edit_modal");
  var modal = document.getElementById("js_template_modal");
  var loader = document.querySelector(".loader");
  var newBtn = fixed_bg.cloneNode(true);
  fixed_bg.parentNode.replaceChild(newBtn, fixed_bg);
  newBtn.addEventListener("click", function () {
    inputElement.value = "";
    if (!loader.classList.contains("hidden")) {
      return;
    }
    imageEditModal.classList.add("hidden");
    newBtn.classList.add("hidden");
    modal.classList.remove("hidden");
  });
};

/***/ }),

/***/ "./resources/js/module/component/ui/FormController.js":
/*!************************************************************!*\
  !*** ./resources/js/module/component/ui/FormController.js ***!
  \************************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _modalOperation_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../modalOperation.js */ "./resources/js/module/component/modalOperation.js");
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _classCallCheck(a, n) { if (!(a instanceof n)) throw new TypeError("Cannot call a class as a function"); }
function _defineProperties(e, r) { for (var t = 0; t < r.length; t++) { var o = r[t]; o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(e, _toPropertyKey(o.key), o); } }
function _createClass(e, r, t) { return r && _defineProperties(e.prototype, r), t && _defineProperties(e, t), Object.defineProperty(e, "prototype", { writable: !1 }), e; }
function _toPropertyKey(t) { var i = _toPrimitive(t, "string"); return "symbol" == _typeof(i) ? i : i + ""; }
function _toPrimitive(t, r) { if ("object" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || "default"); if ("object" != _typeof(i)) return i; throw new TypeError("@@toPrimitive must return a primitive value."); } return ("string" === r ? String : Number)(t); }

var FormController = /*#__PURE__*/function () {
  function FormController() {
    _classCallCheck(this, FormController);
  }
  return _createClass(FormController, null, [{
    key: "initializeInput",
    value: function initializeInput() {
      var inputField = document.querySelector(".js_message_input");
      inputField.value = "";
    }
  }, {
    key: "initializeImageCropInput",
    value: function initializeImageCropInput() {
      var inputFiled = document.getElementById("js_url_input");
      var button = document.getElementById("js_change_area");
      var submitButton = document.querySelector(".preview_submit_btn ");
      var choices = document.getElementsByName('choice');
      document.querySelector(".js_image_error").classList.add("hidden");
      choices.forEach(function (choice) {
        if (choice.value === "off") {
          choice.checked = true;
        } else {
          choice.checked = false;
        }
      });
      var url_wrapper = document.getElementById("js_url_setting");
      url_wrapper.classList.add("hidden");
      button.classList.add("disabled_btn");
      if (button.innerHTML === "選択範囲変更") {
        button.style.backgroundColor = "#fff";
        button.innerHTML = "選択範囲確定";
      }
      submitButton.classList.remove("disabled_btn");
      inputFiled.value = "";
    }
  }, {
    key: "initializeFileUpload",
    value: function initializeFileUpload() {
      document.querySelector(".js_upload").value = "";
    }
  }, {
    key: "initializePreviewList",
    value: function initializePreviewList() {
      document.querySelector(".js_accordion_wrapper").innerHTML = "";
    }
  }, {
    key: "setupTextToggle",
    value: function setupTextToggle() {
      var radioBtns = document.querySelectorAll(".js_display_radio");
      var textInput = document.querySelector(".js_create_text");
      var textElement = document.querySelector(".js_line_text_input");
      radioBtns.forEach(function (radioBtn) {
        radioBtn.addEventListener("change", function (e) {
          textInput.classList.toggle("hidden", e.target.value === "0");
          if (e.target.value === "0") {
            textElement.value = "";
          }
        });
      });
    }
  }, {
    key: "templateImageStyle",
    value: function templateImageStyle(fileInput, objectURL) {
      var imageElement = fileInput.parentElement.querySelector(".image_element");
      var placeholderText = fileInput.parentElement.querySelector(".image-placeholder-txt");

      // 画像プレビューを設定
      imageElement.src = objectURL;
      imageElement.classList.add("active");
      placeholderText.classList.add("hidden");
    }
  }, {
    key: "showCropperSetting",
    value: function showCropperSetting() {
      var setting = document.getElementById("js_url_setting");
      setting.classList.remove("hidden");
      document.getElementById("js_preview_submit_btn").classList.add("disabled_btn");
      var checkOff = document.getElementById("flexRadioDefault1");
      var checkOn = document.getElementById("flexRadioDefault2");
      checkOff.checked = false;
      checkOn.checked = true;
      (0,_modalOperation_js__WEBPACK_IMPORTED_MODULE_0__.close_loader)();
    }
  }]);
}();
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (FormController);

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!*****************************************!*\
  !*** ./resources/js/test_sender_add.js ***!
  \*****************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _module_component_modalOperation_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./module/component/modalOperation.js */ "./resources/js/module/component/modalOperation.js");

var add_sender_btn = document.getElementById("js_create_test_sender_btn");
add_sender_btn.addEventListener("click", function () {
  (0,_module_component_modalOperation_js__WEBPACK_IMPORTED_MODULE_0__.open_modal)(document.getElementById("js_test_sender_add"));
});
})();

/******/ })()
;