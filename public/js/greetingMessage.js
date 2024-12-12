/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/module/component/broadcastMessageOperations.js":
/*!*********************************************************************!*\
  !*** ./resources/js/module/component/broadcastMessageOperations.js ***!
  \*********************************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   deleteList: () => (/* binding */ deleteList),
/* harmony export */   displayMessageToList: () => (/* binding */ displayMessageToList),
/* harmony export */   dragAndDrop: () => (/* binding */ dragAndDrop),
/* harmony export */   hasValue: () => (/* binding */ hasValue),
/* harmony export */   hideErrorMsg: () => (/* binding */ hideErrorMsg)
/* harmony export */ });
/* harmony import */ var _elementTemplate_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./elementTemplate.js */ "./resources/js/module/component/elementTemplate.js");

var displayMessageToList = function displayMessageToList(message, src, index, className, id) {
  // メッセージ表示リストの親要素を取得
  var parentElement = document.querySelector(".".concat(className));
  // 親要素の子要素をすべて取得し、その数を取得する
  var elementLength = parentElement.querySelectorAll(".js_card").length;
  // テキストが最大文字超えていたら...にする

  var heading;
  var display;
  var type;
  if (message) {
    var MAX_LENGTH = 20;
    heading = message.length > MAX_LENGTH ? message.substr(0, MAX_LENGTH) + "..." : message;
    display = message;
    type = "text";
    index = null;
  }
  if (src) {
    heading = "画像";
    display = src;
    type = "img";
    index = index;
  }

  // HTML作成し、親要素に挿入する
  var data = {
    heading: heading,
    display: display,
    elementLength: elementLength,
    type: type,
    index: index
  };
  var template = (0,_elementTemplate_js__WEBPACK_IMPORTED_MODULE_0__.createBroadcastMessageRow)(data, id);
  parentElement.insertAdjacentHTML('beforeend', template);
};
var dragAndDrop = function dragAndDrop(id) {
  var elem = document.getElementById(id);
  Sortable.create(elem, {
    animation: 150,
    handle: '.drag-handle'
  });
};

// メッセージ表示リストから削除する処理
var deleteList = function deleteList(id) {
  var upload = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
  var delete_btns = document.querySelectorAll(".js_deleteList");
  var accordion = document.getElementById(id);
  delete_btns.forEach(function (btn) {
    btn.addEventListener("click", function (e) {
      if (upload) {
        upload.value = "";
      }
      var list_el = e.currentTarget.parentElement.parentElement;
      if (accordion.contains(list_el)) {
        accordion.removeChild(list_el);
      }
    });
  });
};

// 送信ボタンを押す前の値があるかのチェック
var hasValue = function hasValue(id) {
  var accordion = document.getElementById(id);
  var lists = accordion.querySelectorAll(".js_card");
  console.log(lists);
  return lists.length > 0;
};
var hideErrorMsg = function hideErrorMsg() {
  var error_el = document.querySelector(".js_broadcast_error");
  if (!error_el.classList.contains("hidden")) error_el.classList.add("hidden");
};

/***/ }),

/***/ "./resources/js/module/component/elementTemplate.js":
/*!**********************************************************!*\
  !*** ./resources/js/module/component/elementTemplate.js ***!
  \**********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   createAccountDataRow: () => (/* binding */ createAccountDataRow),
/* harmony export */   createBroadcastMessageRow: () => (/* binding */ createBroadcastMessageRow),
/* harmony export */   createMessageRow: () => (/* binding */ createMessageRow),
/* harmony export */   createMessageRowForFetch: () => (/* binding */ createMessageRowForFetch)
/* harmony export */ });
/* harmony import */ var _util_formatDate_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../util/formatDate.js */ "./resources/js/module/util/formatDate.js");

var createMessageRowForFetch = function createMessageRowForFetch(res, admin_account_id, sender_uuid) {
  var createdAtTokyo = (0,_util_formatDate_js__WEBPACK_IMPORTED_MODULE_0__.formateDateToAsia)(res["created_at"]);
  var latestMessageDate = res["latest_message_date"] ? (0,_util_formatDate_js__WEBPACK_IMPORTED_MODULE_0__.formateDateToAsia)(res["latest_message_date"]) : "";
  var display = res["unread_count"] > 0 ? "flex" : "none";
  return "\n            <tr data-id=".concat(sender_uuid, " class=\"js_chatUser_id\">\n                  <th scope=\"row\"><input type=\"checkbox\" id=\"checkbox3\" name=\"option3\" value=").concat(res["id"], "></th>\n                  <td w20>").concat(res["line_name"], "</td>\n                  <td data-id=").concat(res["id"], ">\n                        <div class=\"message_count js_message_count\" style=\"display:").concat(display, "; font-weight: bold;\">").concat(res["unread_count"], "</div>\n                  </td>\n                  <td class=\"js_latest_message_date\">").concat(latestMessageDate, "</td>\n                  <td>").concat(createdAtTokyo, "</td>\n                  <td class=\"operation\">\n                        <button class=\"operation_icon\"><a href=\"https://chat-system.info/").concat(admin_account_id, "/").concat(res["id"], "\"><img src=\"/img/icons8-message-24.png\" alt=\"\"></a></button>\n                        <button class=\"operation_icon js_edit_user_btn\" data-id=").concat(res["id"], "><img src=\"/img/icons8-edit-24.png\" alt=\"\"></button>\n                        <button class=\"operation_icon js_block_btn\" data-uuid=").concat(res["entity_uuid"], " data-name=").concat(res["line_name"], " data-id=").concat(res["id"], "><img src=\"/img/icons8-no-entry-24.png\" alt=\"\"></button>\n                  </td>\n            </tr>\n      ");
};
var createMessageRow = function createMessageRow(res, admin_account_id, sender_uuid) {
  var createdAtTokyo = (0,_util_formatDate_js__WEBPACK_IMPORTED_MODULE_0__.formateDateToAsia)(res[0]["created_at"]);
  var latestMessageDate = (0,_util_formatDate_js__WEBPACK_IMPORTED_MODULE_0__.formateDateToAsia)();
  var display = res[0]["unread_count"] > 0 ? "flex" : "none";
  return "\n            <tr data-id=".concat(sender_uuid, " class=\"js_chatUser_id\">\n                  <th scope=\"row\"><input type=\"checkbox\" id=\"checkbox3\" name=\"option3\" value=").concat(res[0]["id"], "></th>\n                  <td w20>").concat(res[0]["line_name"], "</td>\n                  <td data-id=").concat(res[0]["id"], ">\n                        <div class=\"message_count js_message_count\" style=\"display:").concat(display, "; font-weight: bold;\">").concat(res[0]["unread_count"], "</div>\n                  </td>\n                  <td class=\"js_latest_message_date\">").concat(latestMessageDate, "</td>\n                  <td>").concat(createdAtTokyo, "</td>\n                  <td class=\"operation\">\n                        <button class=\"operation_icon\"><a href=\"https://chat-system.info/").concat(admin_account_id, "/").concat(res[0]["id"], "\"><img src=\"/img/icons8-message-24.png\" alt=\"\"></a></button>\n                        <button class=\"operation_icon js_edit_user_btn\" data-id=").concat(res[0]["id"], "><img src=\"/img/icons8-edit-24.png\" alt=\"\"></button>\n                        <button class=\"operation_icon js_block_btn\" data-uuid=").concat(res[0]["entity_uuid"], " data-name=").concat(res[0]["line_name"], " data-id=").concat(res[0]["id"], "><img src=\"/img/icons8-no-entry-24.png\" alt=\"\"></button>\n                  </td>\n            </tr>\n      ");
};
var createBroadcastMessageRow = function createBroadcastMessageRow(data, id) {
  // 改行を<br>タグに変換
  var displayedData = data.type == "text" ? data.display.replace(/\n/g, '<br>') : "<img data-file-index='".concat(data.index, "' src='").concat(data.display, "' class=\"displayImg js_img\">");
  return "\n            <div class=\"card js_card mb-2\">\n                  <div class=\"card-header\" id=\"heading".concat(data.elementLength, "\">\n                        <div class=\"card-header-left\">\n                              <img src=\"/img/icons8-drag-25.png\" class=\"drag-handle\" style =\"width: 20px;\"/>\n                              <h5 class=\"mb-0\">\n                                    <button class=\"btn collapsed\" data-toggle=\"collapse\" data-target=\"#collapse").concat(data.elementLength, "\" aria-expanded=\"false\" aria-controls=\"collapse").concat(data.elementLength, "\">\n                                          ").concat(data.heading, "\n                                    </button>\n                              </h5>\n                        </div>\n                        <p class=\"js_deleteList\">\xD7</p>\n                  </div>\n            \n                  <div id=\"collapse").concat(data.elementLength, "\" class=\"collapse\" aria-labelledby=\"heading").concat(data.elementLength, "\" data-parent=\"#").concat(id, "\">\n                        <div class=\"card-body js_data\"data-id=\"").concat(data.elementLength + 1, "\">").concat(displayedData, "</div>\n                  </div>\n            </div>\n      ");
};
var createAccountDataRow = function createAccountDataRow(res, categories) {
  var _res$latest_message_d;
  var style = res["unread_count"] > 0 ? "flex" : "none";
  var statusMap = {
    "1": "使用中",
    "2": "未使用",
    "3": "停止",
    "4": "バン"
  };
  var status = statusMap[res["account_status"]];
  var createdAtTokyo = (0,_util_formatDate_js__WEBPACK_IMPORTED_MODULE_0__.formateDateToAsia)(res["created_at"]);
  return "\n            <tr class=\"js_account_id\" data-id=\"".concat(res["entity_uuid"], "\">\n                  <th scope=\"row\"><input type=\"checkbox\" id=\"checkbox3\" name=\"option3\" value=\"3\"></th>\n                  <td w20>").concat(res["account_name"], "</td>\n\n                  <td class=\" text-center total_message-count\">\n                        <div class=\"message_count js_mesage_count js_total_count\" style=\"display: ").concat(style, "; font-weight: bold;\">").concat(res["unread_count"], "</div>\n                  </td>\n                  <td data-id=").concat(res["id"], " class=\"js_status\" style=\"color: #008000; cursor: pointer;\">\n                        <div class=\"btn-group\">\n                              <button class=\"btn btn-secondary btn-sm dropdown-toggle js_status_btn\" type=\"button\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">\n                                    ").concat(status, "\n                              </button>\n                              <ul class=\"dropdown-menu\">\n                                    ").concat(categories.filter(function (category) {
    return category.status !== status;
  }).map(function (category) {
    return "\n                                                <li class=\"dropdown-item js_status_choices\" \n                                                      data-current-status=\"".concat(status, "\"\n                                                      data-status-name=\"").concat(category.status, "\"\n                                                      data-status-id=\"").concat(category.id, "\"\n                                                      data-account-id=\"").concat(res["id"], "\">\n                                                      ").concat(category.status, "\n                                                </li>\n                                    ");
  }).join(''), "\n                              </ul>\n                        </div>\n                  </td>\n                  <td class=\"js_latest_message_date\">").concat((_res$latest_message_d = res["latest_message_date"]) !== null && _res$latest_message_d !== void 0 ? _res$latest_message_d : "", "</td>\n                  <td>").concat(createdAtTokyo, "</td>\n                  <td class=\"operation\">\n                        <a href=\"https://chat-manager.info/account/show/").concat(res["id"], "\"><button class=\"operation_icon\"><img src=\"/img/icons8-user-24.png\" alt=\"\"></button></a>\n                        <button class=\"operation_icon js_edit_account_btn\" data-id=").concat(res["id"], "><img src=\"/img/icons8-edit-24.png\" alt=\"\"></button>\n                        <button class=\"operation_icon js_send_message_btn\" data-id=").concat(res["id"], "><img src=\"/img/icons8-send-24.png\" alt=\"\"></button>\n                        <button class=\"operation_icon js_delete_account_btn\" type=\"submit\" data-id=").concat(res["id"], " data-name=").concat(res["account_name"], "><img src=\"/img/icons8-delete-24.png\" alt=\"\"></button>\n                  </td>\n            </tr>\n      ");
};

/***/ }),

/***/ "./resources/js/module/component/modalOperation.js":
/*!*********************************************************!*\
  !*** ./resources/js/module/component/modalOperation.js ***!
  \*********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   close_modal: () => (/* binding */ close_modal),
/* harmony export */   close_modal_by_click: () => (/* binding */ close_modal_by_click),
/* harmony export */   open_modal: () => (/* binding */ open_modal)
/* harmony export */ });
var open_modal = function open_modal(modal) {
  document.querySelector(".bg").classList.remove("hidden");
  modal.classList.remove("hidden");
};
var close_modal = function close_modal() {
  var bg = document.querySelector(".bg");
  var modals = document.querySelectorAll(".js_modal");
  var alerts = document.querySelectorAll(".js_alert_danger");
  var loader = document.querySelector(".loader");
  bg.addEventListener("click", function () {
    bg.classList.add("hidden");
    loader.classList.add("hidden");
    modals.forEach(function (modal) {
      modal.classList.add("hidden");
    });
    if (alerts) {
      alerts.forEach(function (alert) {
        alert.style.display = "none";
      });
    }
  });
};
var close_modal_by_click = function close_modal_by_click(modal, btn) {
  var bg = document.querySelector(".bg");
  btn.addEventListener("click", function () {
    bg.classList.add("hidden");
    modal.classList.add("hidden");
  });
};

/***/ }),

/***/ "./resources/js/module/util/fetch.js":
/*!*******************************************!*\
  !*** ./resources/js/module/util/fetch.js ***!
  \*******************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   fetchGetOperation: () => (/* binding */ fetchGetOperation),
/* harmony export */   fetchPostOperation: () => (/* binding */ fetchPostOperation)
/* harmony export */ });
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _regeneratorRuntime() { "use strict"; /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/facebook/regenerator/blob/main/LICENSE */ _regeneratorRuntime = function _regeneratorRuntime() { return e; }; var t, e = {}, r = Object.prototype, n = r.hasOwnProperty, o = Object.defineProperty || function (t, e, r) { t[e] = r.value; }, i = "function" == typeof Symbol ? Symbol : {}, a = i.iterator || "@@iterator", c = i.asyncIterator || "@@asyncIterator", u = i.toStringTag || "@@toStringTag"; function define(t, e, r) { return Object.defineProperty(t, e, { value: r, enumerable: !0, configurable: !0, writable: !0 }), t[e]; } try { define({}, ""); } catch (t) { define = function define(t, e, r) { return t[e] = r; }; } function wrap(t, e, r, n) { var i = e && e.prototype instanceof Generator ? e : Generator, a = Object.create(i.prototype), c = new Context(n || []); return o(a, "_invoke", { value: makeInvokeMethod(t, r, c) }), a; } function tryCatch(t, e, r) { try { return { type: "normal", arg: t.call(e, r) }; } catch (t) { return { type: "throw", arg: t }; } } e.wrap = wrap; var h = "suspendedStart", l = "suspendedYield", f = "executing", s = "completed", y = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} var p = {}; define(p, a, function () { return this; }); var d = Object.getPrototypeOf, v = d && d(d(values([]))); v && v !== r && n.call(v, a) && (p = v); var g = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(p); function defineIteratorMethods(t) { ["next", "throw", "return"].forEach(function (e) { define(t, e, function (t) { return this._invoke(e, t); }); }); } function AsyncIterator(t, e) { function invoke(r, o, i, a) { var c = tryCatch(t[r], t, o); if ("throw" !== c.type) { var u = c.arg, h = u.value; return h && "object" == _typeof(h) && n.call(h, "__await") ? e.resolve(h.__await).then(function (t) { invoke("next", t, i, a); }, function (t) { invoke("throw", t, i, a); }) : e.resolve(h).then(function (t) { u.value = t, i(u); }, function (t) { return invoke("throw", t, i, a); }); } a(c.arg); } var r; o(this, "_invoke", { value: function value(t, n) { function callInvokeWithMethodAndArg() { return new e(function (e, r) { invoke(t, n, e, r); }); } return r = r ? r.then(callInvokeWithMethodAndArg, callInvokeWithMethodAndArg) : callInvokeWithMethodAndArg(); } }); } function makeInvokeMethod(e, r, n) { var o = h; return function (i, a) { if (o === f) throw Error("Generator is already running"); if (o === s) { if ("throw" === i) throw a; return { value: t, done: !0 }; } for (n.method = i, n.arg = a;;) { var c = n.delegate; if (c) { var u = maybeInvokeDelegate(c, n); if (u) { if (u === y) continue; return u; } } if ("next" === n.method) n.sent = n._sent = n.arg;else if ("throw" === n.method) { if (o === h) throw o = s, n.arg; n.dispatchException(n.arg); } else "return" === n.method && n.abrupt("return", n.arg); o = f; var p = tryCatch(e, r, n); if ("normal" === p.type) { if (o = n.done ? s : l, p.arg === y) continue; return { value: p.arg, done: n.done }; } "throw" === p.type && (o = s, n.method = "throw", n.arg = p.arg); } }; } function maybeInvokeDelegate(e, r) { var n = r.method, o = e.iterator[n]; if (o === t) return r.delegate = null, "throw" === n && e.iterator["return"] && (r.method = "return", r.arg = t, maybeInvokeDelegate(e, r), "throw" === r.method) || "return" !== n && (r.method = "throw", r.arg = new TypeError("The iterator does not provide a '" + n + "' method")), y; var i = tryCatch(o, e.iterator, r.arg); if ("throw" === i.type) return r.method = "throw", r.arg = i.arg, r.delegate = null, y; var a = i.arg; return a ? a.done ? (r[e.resultName] = a.value, r.next = e.nextLoc, "return" !== r.method && (r.method = "next", r.arg = t), r.delegate = null, y) : a : (r.method = "throw", r.arg = new TypeError("iterator result is not an object"), r.delegate = null, y); } function pushTryEntry(t) { var e = { tryLoc: t[0] }; 1 in t && (e.catchLoc = t[1]), 2 in t && (e.finallyLoc = t[2], e.afterLoc = t[3]), this.tryEntries.push(e); } function resetTryEntry(t) { var e = t.completion || {}; e.type = "normal", delete e.arg, t.completion = e; } function Context(t) { this.tryEntries = [{ tryLoc: "root" }], t.forEach(pushTryEntry, this), this.reset(!0); } function values(e) { if (e || "" === e) { var r = e[a]; if (r) return r.call(e); if ("function" == typeof e.next) return e; if (!isNaN(e.length)) { var o = -1, i = function next() { for (; ++o < e.length;) if (n.call(e, o)) return next.value = e[o], next.done = !1, next; return next.value = t, next.done = !0, next; }; return i.next = i; } } throw new TypeError(_typeof(e) + " is not iterable"); } return GeneratorFunction.prototype = GeneratorFunctionPrototype, o(g, "constructor", { value: GeneratorFunctionPrototype, configurable: !0 }), o(GeneratorFunctionPrototype, "constructor", { value: GeneratorFunction, configurable: !0 }), GeneratorFunction.displayName = define(GeneratorFunctionPrototype, u, "GeneratorFunction"), e.isGeneratorFunction = function (t) { var e = "function" == typeof t && t.constructor; return !!e && (e === GeneratorFunction || "GeneratorFunction" === (e.displayName || e.name)); }, e.mark = function (t) { return Object.setPrototypeOf ? Object.setPrototypeOf(t, GeneratorFunctionPrototype) : (t.__proto__ = GeneratorFunctionPrototype, define(t, u, "GeneratorFunction")), t.prototype = Object.create(g), t; }, e.awrap = function (t) { return { __await: t }; }, defineIteratorMethods(AsyncIterator.prototype), define(AsyncIterator.prototype, c, function () { return this; }), e.AsyncIterator = AsyncIterator, e.async = function (t, r, n, o, i) { void 0 === i && (i = Promise); var a = new AsyncIterator(wrap(t, r, n, o), i); return e.isGeneratorFunction(r) ? a : a.next().then(function (t) { return t.done ? t.value : a.next(); }); }, defineIteratorMethods(g), define(g, u, "Generator"), define(g, a, function () { return this; }), define(g, "toString", function () { return "[object Generator]"; }), e.keys = function (t) { var e = Object(t), r = []; for (var n in e) r.push(n); return r.reverse(), function next() { for (; r.length;) { var t = r.pop(); if (t in e) return next.value = t, next.done = !1, next; } return next.done = !0, next; }; }, e.values = values, Context.prototype = { constructor: Context, reset: function reset(e) { if (this.prev = 0, this.next = 0, this.sent = this._sent = t, this.done = !1, this.delegate = null, this.method = "next", this.arg = t, this.tryEntries.forEach(resetTryEntry), !e) for (var r in this) "t" === r.charAt(0) && n.call(this, r) && !isNaN(+r.slice(1)) && (this[r] = t); }, stop: function stop() { this.done = !0; var t = this.tryEntries[0].completion; if ("throw" === t.type) throw t.arg; return this.rval; }, dispatchException: function dispatchException(e) { if (this.done) throw e; var r = this; function handle(n, o) { return a.type = "throw", a.arg = e, r.next = n, o && (r.method = "next", r.arg = t), !!o; } for (var o = this.tryEntries.length - 1; o >= 0; --o) { var i = this.tryEntries[o], a = i.completion; if ("root" === i.tryLoc) return handle("end"); if (i.tryLoc <= this.prev) { var c = n.call(i, "catchLoc"), u = n.call(i, "finallyLoc"); if (c && u) { if (this.prev < i.catchLoc) return handle(i.catchLoc, !0); if (this.prev < i.finallyLoc) return handle(i.finallyLoc); } else if (c) { if (this.prev < i.catchLoc) return handle(i.catchLoc, !0); } else { if (!u) throw Error("try statement without catch or finally"); if (this.prev < i.finallyLoc) return handle(i.finallyLoc); } } } }, abrupt: function abrupt(t, e) { for (var r = this.tryEntries.length - 1; r >= 0; --r) { var o = this.tryEntries[r]; if (o.tryLoc <= this.prev && n.call(o, "finallyLoc") && this.prev < o.finallyLoc) { var i = o; break; } } i && ("break" === t || "continue" === t) && i.tryLoc <= e && e <= i.finallyLoc && (i = null); var a = i ? i.completion : {}; return a.type = t, a.arg = e, i ? (this.method = "next", this.next = i.finallyLoc, y) : this.complete(a); }, complete: function complete(t, e) { if ("throw" === t.type) throw t.arg; return "break" === t.type || "continue" === t.type ? this.next = t.arg : "return" === t.type ? (this.rval = this.arg = t.arg, this.method = "return", this.next = "end") : "normal" === t.type && e && (this.next = e), y; }, finish: function finish(t) { for (var e = this.tryEntries.length - 1; e >= 0; --e) { var r = this.tryEntries[e]; if (r.finallyLoc === t) return this.complete(r.completion, r.afterLoc), resetTryEntry(r), y; } }, "catch": function _catch(t) { for (var e = this.tryEntries.length - 1; e >= 0; --e) { var r = this.tryEntries[e]; if (r.tryLoc === t) { var n = r.completion; if ("throw" === n.type) { var o = n.arg; resetTryEntry(r); } return o; } } throw Error("illegal catch attempt"); }, delegateYield: function delegateYield(e, r, n) { return this.delegate = { iterator: values(e), resultName: r, nextLoc: n }, "next" === this.method && (this.arg = t), y; } }, e; }
function asyncGeneratorStep(n, t, e, r, o, a, c) { try { var i = n[a](c), u = i.value; } catch (n) { return void e(n); } i.done ? t(u) : Promise.resolve(u).then(r, o); }
function _asyncToGenerator(n) { return function () { var t = this, e = arguments; return new Promise(function (r, o) { var a = n.apply(t, e); function _next(n) { asyncGeneratorStep(a, r, o, _next, _throw, "next", n); } function _throw(n) { asyncGeneratorStep(a, r, o, _next, _throw, "throw", n); } _next(void 0); }); }; }
var fetchPostOperation = function fetchPostOperation(data, url) {
  return fetch("".concat(url), {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify(data)
  }).then(/*#__PURE__*/function () {
    var _ref = _asyncToGenerator(/*#__PURE__*/_regeneratorRuntime().mark(function _callee(response) {
      var errorMessage;
      return _regeneratorRuntime().wrap(function _callee$(_context) {
        while (1) switch (_context.prev = _context.next) {
          case 0:
            if (!(response.status === 204)) {
              _context.next = 2;
              break;
            }
            return _context.abrupt("return");
          case 2:
            if (response.ok) {
              _context.next = 6;
              break;
            }
            _context.next = 5;
            return response.text();
          case 5:
            errorMessage = _context.sent;
          case 6:
            return _context.abrupt("return", response.json());
          case 7:
          case "end":
            return _context.stop();
        }
      }, _callee);
    }));
    return function (_x) {
      return _ref.apply(this, arguments);
    };
  }())["catch"](function (error) {
    console.log(error);
  });
};
var fetchGetOperation = function fetchGetOperation(url) {
  return fetch("".concat(url), {
    method: "GET",
    headers: {
      "Content-Type": "application/json"
    }
  }).then(/*#__PURE__*/function () {
    var _ref2 = _asyncToGenerator(/*#__PURE__*/_regeneratorRuntime().mark(function _callee2(response) {
      var errorMessage;
      return _regeneratorRuntime().wrap(function _callee2$(_context2) {
        while (1) switch (_context2.prev = _context2.next) {
          case 0:
            if (!(response.status === 204)) {
              _context2.next = 2;
              break;
            }
            return _context2.abrupt("return");
          case 2:
            if (response.ok) {
              _context2.next = 6;
              break;
            }
            _context2.next = 5;
            return response.text();
          case 5:
            errorMessage = _context2.sent;
          case 6:
            return _context2.abrupt("return", response.json());
          case 7:
          case "end":
            return _context2.stop();
        }
      }, _callee2);
    }));
    return function (_x2) {
      return _ref2.apply(this, arguments);
    };
  }()).then(function (data) {
    return data;
  })["catch"](function (error) {
    console.error("エラーが発生しました:", error.message);
  });
};

/***/ }),

/***/ "./resources/js/module/util/formatDate.js":
/*!************************************************!*\
  !*** ./resources/js/module/util/formatDate.js ***!
  \************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   formateDateToAsia: () => (/* binding */ formateDateToAsia)
/* harmony export */ });
var formateDateToAsia = function formateDateToAsia() {
  var createdAt = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;
  // Date オブジェクトに変換

  var date = createdAt ? new Date(createdAt) : new Date();

  // Asia/Tokyo のタイムゾーンに変換（YYYY-MM-DD HH:MM:SS形式）
  var options = {
    timeZone: 'Asia/Tokyo',
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
    hour12: false // 24時間形式にする
  };

  // toLocaleStringで整形しながら表示
  var dateTokyo = date.toLocaleString('ja-JP', options).replace(/\//g, '-').replace(',', '');
  return dateTokyo;
};

/***/ }),

/***/ "./resources/js/module/util/messageService.js":
/*!****************************************************!*\
  !*** ./resources/js/module/util/messageService.js ***!
  \****************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   cleanHtmlContent: () => (/* binding */ cleanHtmlContent),
/* harmony export */   prepareMessageData: () => (/* binding */ prepareMessageData)
/* harmony export */ });
var prepareMessageData = function prepareMessageData() {
  // サーバーに送信するデータをすべて取得する
  var body = document.querySelector(".js_message_input").value;
  var formatted_body = body.replace(/\n/g, '<br>'); // 改行文字を <br> タグに置き換える
  var admin_account_id = document.getElementById("js_account_id").value;
  return {
    body: body,
    formatted_body: formatted_body,
    admin_account_id: admin_account_id
  };
};

//<br> タグを含むすべての HTML タグを除去し、適切な改行を維持する
var cleanHtmlContent = function cleanHtmlContent(html) {
  // 1. <br>タグを改行文字に置換
  var text = html.replace(/<br\s*\/?>/gi, '\n');

  // 2. その他のHTMLタグを除去
  text = text.replace(/<[^>]+>/g, '');

  // 3. HTMLエンティティをデコード
  var textarea = document.createElement('textarea');
  textarea.innerHTML = text;
  text = textarea.value;

  // 4. 連続する改行を1つの改行に置換
  text = text.replace(/\n{3,}/g, '\n\n');

  // 5. 前後の空白を除去
  return text.trim();
};

/***/ }),

/***/ "./resources/js/module/util/processAndResizeImage.js":
/*!***********************************************************!*\
  !*** ./resources/js/module/util/processAndResizeImage.js ***!
  \***********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   fileOperation: () => (/* binding */ fileOperation),
/* harmony export */   resizeImage: () => (/* binding */ resizeImage)
/* harmony export */ });
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _regeneratorRuntime() { "use strict"; /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/facebook/regenerator/blob/main/LICENSE */ _regeneratorRuntime = function _regeneratorRuntime() { return e; }; var t, e = {}, r = Object.prototype, n = r.hasOwnProperty, o = Object.defineProperty || function (t, e, r) { t[e] = r.value; }, i = "function" == typeof Symbol ? Symbol : {}, a = i.iterator || "@@iterator", c = i.asyncIterator || "@@asyncIterator", u = i.toStringTag || "@@toStringTag"; function define(t, e, r) { return Object.defineProperty(t, e, { value: r, enumerable: !0, configurable: !0, writable: !0 }), t[e]; } try { define({}, ""); } catch (t) { define = function define(t, e, r) { return t[e] = r; }; } function wrap(t, e, r, n) { var i = e && e.prototype instanceof Generator ? e : Generator, a = Object.create(i.prototype), c = new Context(n || []); return o(a, "_invoke", { value: makeInvokeMethod(t, r, c) }), a; } function tryCatch(t, e, r) { try { return { type: "normal", arg: t.call(e, r) }; } catch (t) { return { type: "throw", arg: t }; } } e.wrap = wrap; var h = "suspendedStart", l = "suspendedYield", f = "executing", s = "completed", y = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} var p = {}; define(p, a, function () { return this; }); var d = Object.getPrototypeOf, v = d && d(d(values([]))); v && v !== r && n.call(v, a) && (p = v); var g = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(p); function defineIteratorMethods(t) { ["next", "throw", "return"].forEach(function (e) { define(t, e, function (t) { return this._invoke(e, t); }); }); } function AsyncIterator(t, e) { function invoke(r, o, i, a) { var c = tryCatch(t[r], t, o); if ("throw" !== c.type) { var u = c.arg, h = u.value; return h && "object" == _typeof(h) && n.call(h, "__await") ? e.resolve(h.__await).then(function (t) { invoke("next", t, i, a); }, function (t) { invoke("throw", t, i, a); }) : e.resolve(h).then(function (t) { u.value = t, i(u); }, function (t) { return invoke("throw", t, i, a); }); } a(c.arg); } var r; o(this, "_invoke", { value: function value(t, n) { function callInvokeWithMethodAndArg() { return new e(function (e, r) { invoke(t, n, e, r); }); } return r = r ? r.then(callInvokeWithMethodAndArg, callInvokeWithMethodAndArg) : callInvokeWithMethodAndArg(); } }); } function makeInvokeMethod(e, r, n) { var o = h; return function (i, a) { if (o === f) throw Error("Generator is already running"); if (o === s) { if ("throw" === i) throw a; return { value: t, done: !0 }; } for (n.method = i, n.arg = a;;) { var c = n.delegate; if (c) { var u = maybeInvokeDelegate(c, n); if (u) { if (u === y) continue; return u; } } if ("next" === n.method) n.sent = n._sent = n.arg;else if ("throw" === n.method) { if (o === h) throw o = s, n.arg; n.dispatchException(n.arg); } else "return" === n.method && n.abrupt("return", n.arg); o = f; var p = tryCatch(e, r, n); if ("normal" === p.type) { if (o = n.done ? s : l, p.arg === y) continue; return { value: p.arg, done: n.done }; } "throw" === p.type && (o = s, n.method = "throw", n.arg = p.arg); } }; } function maybeInvokeDelegate(e, r) { var n = r.method, o = e.iterator[n]; if (o === t) return r.delegate = null, "throw" === n && e.iterator["return"] && (r.method = "return", r.arg = t, maybeInvokeDelegate(e, r), "throw" === r.method) || "return" !== n && (r.method = "throw", r.arg = new TypeError("The iterator does not provide a '" + n + "' method")), y; var i = tryCatch(o, e.iterator, r.arg); if ("throw" === i.type) return r.method = "throw", r.arg = i.arg, r.delegate = null, y; var a = i.arg; return a ? a.done ? (r[e.resultName] = a.value, r.next = e.nextLoc, "return" !== r.method && (r.method = "next", r.arg = t), r.delegate = null, y) : a : (r.method = "throw", r.arg = new TypeError("iterator result is not an object"), r.delegate = null, y); } function pushTryEntry(t) { var e = { tryLoc: t[0] }; 1 in t && (e.catchLoc = t[1]), 2 in t && (e.finallyLoc = t[2], e.afterLoc = t[3]), this.tryEntries.push(e); } function resetTryEntry(t) { var e = t.completion || {}; e.type = "normal", delete e.arg, t.completion = e; } function Context(t) { this.tryEntries = [{ tryLoc: "root" }], t.forEach(pushTryEntry, this), this.reset(!0); } function values(e) { if (e || "" === e) { var r = e[a]; if (r) return r.call(e); if ("function" == typeof e.next) return e; if (!isNaN(e.length)) { var o = -1, i = function next() { for (; ++o < e.length;) if (n.call(e, o)) return next.value = e[o], next.done = !1, next; return next.value = t, next.done = !0, next; }; return i.next = i; } } throw new TypeError(_typeof(e) + " is not iterable"); } return GeneratorFunction.prototype = GeneratorFunctionPrototype, o(g, "constructor", { value: GeneratorFunctionPrototype, configurable: !0 }), o(GeneratorFunctionPrototype, "constructor", { value: GeneratorFunction, configurable: !0 }), GeneratorFunction.displayName = define(GeneratorFunctionPrototype, u, "GeneratorFunction"), e.isGeneratorFunction = function (t) { var e = "function" == typeof t && t.constructor; return !!e && (e === GeneratorFunction || "GeneratorFunction" === (e.displayName || e.name)); }, e.mark = function (t) { return Object.setPrototypeOf ? Object.setPrototypeOf(t, GeneratorFunctionPrototype) : (t.__proto__ = GeneratorFunctionPrototype, define(t, u, "GeneratorFunction")), t.prototype = Object.create(g), t; }, e.awrap = function (t) { return { __await: t }; }, defineIteratorMethods(AsyncIterator.prototype), define(AsyncIterator.prototype, c, function () { return this; }), e.AsyncIterator = AsyncIterator, e.async = function (t, r, n, o, i) { void 0 === i && (i = Promise); var a = new AsyncIterator(wrap(t, r, n, o), i); return e.isGeneratorFunction(r) ? a : a.next().then(function (t) { return t.done ? t.value : a.next(); }); }, defineIteratorMethods(g), define(g, u, "Generator"), define(g, a, function () { return this; }), define(g, "toString", function () { return "[object Generator]"; }), e.keys = function (t) { var e = Object(t), r = []; for (var n in e) r.push(n); return r.reverse(), function next() { for (; r.length;) { var t = r.pop(); if (t in e) return next.value = t, next.done = !1, next; } return next.done = !0, next; }; }, e.values = values, Context.prototype = { constructor: Context, reset: function reset(e) { if (this.prev = 0, this.next = 0, this.sent = this._sent = t, this.done = !1, this.delegate = null, this.method = "next", this.arg = t, this.tryEntries.forEach(resetTryEntry), !e) for (var r in this) "t" === r.charAt(0) && n.call(this, r) && !isNaN(+r.slice(1)) && (this[r] = t); }, stop: function stop() { this.done = !0; var t = this.tryEntries[0].completion; if ("throw" === t.type) throw t.arg; return this.rval; }, dispatchException: function dispatchException(e) { if (this.done) throw e; var r = this; function handle(n, o) { return a.type = "throw", a.arg = e, r.next = n, o && (r.method = "next", r.arg = t), !!o; } for (var o = this.tryEntries.length - 1; o >= 0; --o) { var i = this.tryEntries[o], a = i.completion; if ("root" === i.tryLoc) return handle("end"); if (i.tryLoc <= this.prev) { var c = n.call(i, "catchLoc"), u = n.call(i, "finallyLoc"); if (c && u) { if (this.prev < i.catchLoc) return handle(i.catchLoc, !0); if (this.prev < i.finallyLoc) return handle(i.finallyLoc); } else if (c) { if (this.prev < i.catchLoc) return handle(i.catchLoc, !0); } else { if (!u) throw Error("try statement without catch or finally"); if (this.prev < i.finallyLoc) return handle(i.finallyLoc); } } } }, abrupt: function abrupt(t, e) { for (var r = this.tryEntries.length - 1; r >= 0; --r) { var o = this.tryEntries[r]; if (o.tryLoc <= this.prev && n.call(o, "finallyLoc") && this.prev < o.finallyLoc) { var i = o; break; } } i && ("break" === t || "continue" === t) && i.tryLoc <= e && e <= i.finallyLoc && (i = null); var a = i ? i.completion : {}; return a.type = t, a.arg = e, i ? (this.method = "next", this.next = i.finallyLoc, y) : this.complete(a); }, complete: function complete(t, e) { if ("throw" === t.type) throw t.arg; return "break" === t.type || "continue" === t.type ? this.next = t.arg : "return" === t.type ? (this.rval = this.arg = t.arg, this.method = "return", this.next = "end") : "normal" === t.type && e && (this.next = e), y; }, finish: function finish(t) { for (var e = this.tryEntries.length - 1; e >= 0; --e) { var r = this.tryEntries[e]; if (r.finallyLoc === t) return this.complete(r.completion, r.afterLoc), resetTryEntry(r), y; } }, "catch": function _catch(t) { for (var e = this.tryEntries.length - 1; e >= 0; --e) { var r = this.tryEntries[e]; if (r.tryLoc === t) { var n = r.completion; if ("throw" === n.type) { var o = n.arg; resetTryEntry(r); } return o; } } throw Error("illegal catch attempt"); }, delegateYield: function delegateYield(e, r, n) { return this.delegate = { iterator: values(e), resultName: r, nextLoc: n }, "next" === this.method && (this.arg = t), y; } }, e; }
function asyncGeneratorStep(n, t, e, r, o, a, c) { try { var i = n[a](c), u = i.value; } catch (n) { return void e(n); } i.done ? t(u) : Promise.resolve(u).then(r, o); }
function _asyncToGenerator(n) { return function () { var t = this, e = arguments; return new Promise(function (r, o) { var a = n.apply(t, e); function _next(n) { asyncGeneratorStep(a, r, o, _next, _throw, "next", n); } function _throw(n) { asyncGeneratorStep(a, r, o, _next, _throw, "throw", n); } _next(void 0); }); }; }
var resizeImage = function resizeImage(file) {
  var maxWidth = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 160;
  return new Promise(function (resolve, reject) {
    var reader = new FileReader();
    reader.onload = function (e) {
      var img = new Image();
      img.src = e.target.result;
      img.onload = function () {
        // 圧縮を省略し、元の形式とクオリティを維持
        var resizedImage = e.target.result;
        resolve(resizedImage);
      };
      img.onerror = function () {
        return reject(new Error('画像の読み込みに失敗しました。'));
      };
    };
    reader.onerror = function () {
      return reject(new Error('ファイルの読み込みに失敗しました。'));
    };
    reader.readAsDataURL(file);
  });
};
var fileOperation = /*#__PURE__*/function () {
  var _ref = _asyncToGenerator(/*#__PURE__*/_regeneratorRuntime().mark(function _callee() {
    var fileInput, file, resizedImage;
    return _regeneratorRuntime().wrap(function _callee$(_context) {
      while (1) switch (_context.prev = _context.next) {
        case 0:
          fileInput = document.getElementById('fileInput'); // 適切なIDを使用してください
          file = fileInput.files[0];
          _context.prev = 2;
          _context.next = 5;
          return resizeImage(file);
        case 5:
          resizedImage = _context.sent;
          _context.next = 11;
          break;
        case 8:
          _context.prev = 8;
          _context.t0 = _context["catch"](2);
          alert(_context.t0.message);
        case 11:
        case "end":
          return _context.stop();
      }
    }, _callee, null, [[2, 8]]);
  }));
  return function fileOperation() {
    return _ref.apply(this, arguments);
  };
}();

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
  !*** ./resources/js/greetingMessage.js ***!
  \*****************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _module_component_broadcastMessageOperations_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./module/component/broadcastMessageOperations.js */ "./resources/js/module/component/broadcastMessageOperations.js");
/* harmony import */ var _module_component_modalOperation_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./module/component/modalOperation.js */ "./resources/js/module/component/modalOperation.js");
/* harmony import */ var _module_util_fetch_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./module/util/fetch.js */ "./resources/js/module/util/fetch.js");
/* harmony import */ var _module_util_messageService_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./module/util/messageService.js */ "./resources/js/module/util/messageService.js");
/* harmony import */ var _module_util_processAndResizeImage_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./module/util/processAndResizeImage.js */ "./resources/js/module/util/processAndResizeImage.js");
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _regeneratorRuntime() { "use strict"; /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/facebook/regenerator/blob/main/LICENSE */ _regeneratorRuntime = function _regeneratorRuntime() { return e; }; var t, e = {}, r = Object.prototype, n = r.hasOwnProperty, o = Object.defineProperty || function (t, e, r) { t[e] = r.value; }, i = "function" == typeof Symbol ? Symbol : {}, a = i.iterator || "@@iterator", c = i.asyncIterator || "@@asyncIterator", u = i.toStringTag || "@@toStringTag"; function define(t, e, r) { return Object.defineProperty(t, e, { value: r, enumerable: !0, configurable: !0, writable: !0 }), t[e]; } try { define({}, ""); } catch (t) { define = function define(t, e, r) { return t[e] = r; }; } function wrap(t, e, r, n) { var i = e && e.prototype instanceof Generator ? e : Generator, a = Object.create(i.prototype), c = new Context(n || []); return o(a, "_invoke", { value: makeInvokeMethod(t, r, c) }), a; } function tryCatch(t, e, r) { try { return { type: "normal", arg: t.call(e, r) }; } catch (t) { return { type: "throw", arg: t }; } } e.wrap = wrap; var h = "suspendedStart", l = "suspendedYield", f = "executing", s = "completed", y = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} var p = {}; define(p, a, function () { return this; }); var d = Object.getPrototypeOf, v = d && d(d(values([]))); v && v !== r && n.call(v, a) && (p = v); var g = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(p); function defineIteratorMethods(t) { ["next", "throw", "return"].forEach(function (e) { define(t, e, function (t) { return this._invoke(e, t); }); }); } function AsyncIterator(t, e) { function invoke(r, o, i, a) { var c = tryCatch(t[r], t, o); if ("throw" !== c.type) { var u = c.arg, h = u.value; return h && "object" == _typeof(h) && n.call(h, "__await") ? e.resolve(h.__await).then(function (t) { invoke("next", t, i, a); }, function (t) { invoke("throw", t, i, a); }) : e.resolve(h).then(function (t) { u.value = t, i(u); }, function (t) { return invoke("throw", t, i, a); }); } a(c.arg); } var r; o(this, "_invoke", { value: function value(t, n) { function callInvokeWithMethodAndArg() { return new e(function (e, r) { invoke(t, n, e, r); }); } return r = r ? r.then(callInvokeWithMethodAndArg, callInvokeWithMethodAndArg) : callInvokeWithMethodAndArg(); } }); } function makeInvokeMethod(e, r, n) { var o = h; return function (i, a) { if (o === f) throw Error("Generator is already running"); if (o === s) { if ("throw" === i) throw a; return { value: t, done: !0 }; } for (n.method = i, n.arg = a;;) { var c = n.delegate; if (c) { var u = maybeInvokeDelegate(c, n); if (u) { if (u === y) continue; return u; } } if ("next" === n.method) n.sent = n._sent = n.arg;else if ("throw" === n.method) { if (o === h) throw o = s, n.arg; n.dispatchException(n.arg); } else "return" === n.method && n.abrupt("return", n.arg); o = f; var p = tryCatch(e, r, n); if ("normal" === p.type) { if (o = n.done ? s : l, p.arg === y) continue; return { value: p.arg, done: n.done }; } "throw" === p.type && (o = s, n.method = "throw", n.arg = p.arg); } }; } function maybeInvokeDelegate(e, r) { var n = r.method, o = e.iterator[n]; if (o === t) return r.delegate = null, "throw" === n && e.iterator["return"] && (r.method = "return", r.arg = t, maybeInvokeDelegate(e, r), "throw" === r.method) || "return" !== n && (r.method = "throw", r.arg = new TypeError("The iterator does not provide a '" + n + "' method")), y; var i = tryCatch(o, e.iterator, r.arg); if ("throw" === i.type) return r.method = "throw", r.arg = i.arg, r.delegate = null, y; var a = i.arg; return a ? a.done ? (r[e.resultName] = a.value, r.next = e.nextLoc, "return" !== r.method && (r.method = "next", r.arg = t), r.delegate = null, y) : a : (r.method = "throw", r.arg = new TypeError("iterator result is not an object"), r.delegate = null, y); } function pushTryEntry(t) { var e = { tryLoc: t[0] }; 1 in t && (e.catchLoc = t[1]), 2 in t && (e.finallyLoc = t[2], e.afterLoc = t[3]), this.tryEntries.push(e); } function resetTryEntry(t) { var e = t.completion || {}; e.type = "normal", delete e.arg, t.completion = e; } function Context(t) { this.tryEntries = [{ tryLoc: "root" }], t.forEach(pushTryEntry, this), this.reset(!0); } function values(e) { if (e || "" === e) { var r = e[a]; if (r) return r.call(e); if ("function" == typeof e.next) return e; if (!isNaN(e.length)) { var o = -1, i = function next() { for (; ++o < e.length;) if (n.call(e, o)) return next.value = e[o], next.done = !1, next; return next.value = t, next.done = !0, next; }; return i.next = i; } } throw new TypeError(_typeof(e) + " is not iterable"); } return GeneratorFunction.prototype = GeneratorFunctionPrototype, o(g, "constructor", { value: GeneratorFunctionPrototype, configurable: !0 }), o(GeneratorFunctionPrototype, "constructor", { value: GeneratorFunction, configurable: !0 }), GeneratorFunction.displayName = define(GeneratorFunctionPrototype, u, "GeneratorFunction"), e.isGeneratorFunction = function (t) { var e = "function" == typeof t && t.constructor; return !!e && (e === GeneratorFunction || "GeneratorFunction" === (e.displayName || e.name)); }, e.mark = function (t) { return Object.setPrototypeOf ? Object.setPrototypeOf(t, GeneratorFunctionPrototype) : (t.__proto__ = GeneratorFunctionPrototype, define(t, u, "GeneratorFunction")), t.prototype = Object.create(g), t; }, e.awrap = function (t) { return { __await: t }; }, defineIteratorMethods(AsyncIterator.prototype), define(AsyncIterator.prototype, c, function () { return this; }), e.AsyncIterator = AsyncIterator, e.async = function (t, r, n, o, i) { void 0 === i && (i = Promise); var a = new AsyncIterator(wrap(t, r, n, o), i); return e.isGeneratorFunction(r) ? a : a.next().then(function (t) { return t.done ? t.value : a.next(); }); }, defineIteratorMethods(g), define(g, u, "Generator"), define(g, a, function () { return this; }), define(g, "toString", function () { return "[object Generator]"; }), e.keys = function (t) { var e = Object(t), r = []; for (var n in e) r.push(n); return r.reverse(), function next() { for (; r.length;) { var t = r.pop(); if (t in e) return next.value = t, next.done = !1, next; } return next.done = !0, next; }; }, e.values = values, Context.prototype = { constructor: Context, reset: function reset(e) { if (this.prev = 0, this.next = 0, this.sent = this._sent = t, this.done = !1, this.delegate = null, this.method = "next", this.arg = t, this.tryEntries.forEach(resetTryEntry), !e) for (var r in this) "t" === r.charAt(0) && n.call(this, r) && !isNaN(+r.slice(1)) && (this[r] = t); }, stop: function stop() { this.done = !0; var t = this.tryEntries[0].completion; if ("throw" === t.type) throw t.arg; return this.rval; }, dispatchException: function dispatchException(e) { if (this.done) throw e; var r = this; function handle(n, o) { return a.type = "throw", a.arg = e, r.next = n, o && (r.method = "next", r.arg = t), !!o; } for (var o = this.tryEntries.length - 1; o >= 0; --o) { var i = this.tryEntries[o], a = i.completion; if ("root" === i.tryLoc) return handle("end"); if (i.tryLoc <= this.prev) { var c = n.call(i, "catchLoc"), u = n.call(i, "finallyLoc"); if (c && u) { if (this.prev < i.catchLoc) return handle(i.catchLoc, !0); if (this.prev < i.finallyLoc) return handle(i.finallyLoc); } else if (c) { if (this.prev < i.catchLoc) return handle(i.catchLoc, !0); } else { if (!u) throw Error("try statement without catch or finally"); if (this.prev < i.finallyLoc) return handle(i.finallyLoc); } } } }, abrupt: function abrupt(t, e) { for (var r = this.tryEntries.length - 1; r >= 0; --r) { var o = this.tryEntries[r]; if (o.tryLoc <= this.prev && n.call(o, "finallyLoc") && this.prev < o.finallyLoc) { var i = o; break; } } i && ("break" === t || "continue" === t) && i.tryLoc <= e && e <= i.finallyLoc && (i = null); var a = i ? i.completion : {}; return a.type = t, a.arg = e, i ? (this.method = "next", this.next = i.finallyLoc, y) : this.complete(a); }, complete: function complete(t, e) { if ("throw" === t.type) throw t.arg; return "break" === t.type || "continue" === t.type ? this.next = t.arg : "return" === t.type ? (this.rval = this.arg = t.arg, this.method = "return", this.next = "end") : "normal" === t.type && e && (this.next = e), y; }, finish: function finish(t) { for (var e = this.tryEntries.length - 1; e >= 0; --e) { var r = this.tryEntries[e]; if (r.finallyLoc === t) return this.complete(r.completion, r.afterLoc), resetTryEntry(r), y; } }, "catch": function _catch(t) { for (var e = this.tryEntries.length - 1; e >= 0; --e) { var r = this.tryEntries[e]; if (r.tryLoc === t) { var n = r.completion; if ("throw" === n.type) { var o = n.arg; resetTryEntry(r); } return o; } } throw Error("illegal catch attempt"); }, delegateYield: function delegateYield(e, r, n) { return this.delegate = { iterator: values(e), resultName: r, nextLoc: n }, "next" === this.method && (this.arg = t), y; } }, e; }
function asyncGeneratorStep(n, t, e, r, o, a, c) { try { var i = n[a](c), u = i.value; } catch (n) { return void e(n); } i.done ? t(u) : Promise.resolve(u).then(r, o); }
function _asyncToGenerator(n) { return function () { var t = this, e = arguments; return new Promise(function (r, o) { var a = n.apply(t, e); function _next(n) { asyncGeneratorStep(a, r, o, _next, _throw, "next", n); } function _throw(n) { asyncGeneratorStep(a, r, o, _next, _throw, "throw", n); } _next(void 0); }); }; }






// グローバル変数
var greeting_btn = document.getElementById("js_create_message_btn");
var modal = document.querySelector(".broadcasting_message_modal");
var greetingMessageInput = document.querySelector(".js_greeting_input");
var display_btn = document.querySelector(".js_greeting_display_btn");
var greetingMessage = "";
greeting_btn.addEventListener("click", function () {
  (0,_module_component_modalOperation_js__WEBPACK_IMPORTED_MODULE_1__.open_modal)(modal);
});

// メッセージの入力ありなしで追加ボタンのスタイルを変更する
greetingMessageInput.addEventListener("input", function (e) {
  (0,_module_component_broadcastMessageOperations_js__WEBPACK_IMPORTED_MODULE_0__.hideErrorMsg)();
  greetingMessage = e.currentTarget.value;
  if (greetingMessage.length > 0) {
    display_btn.classList.remove("disabled_btn");
  } else {
    display_btn.classList.add("disabled_btn");
  }
});

// 追加ボタンを押したらメッセージまたは画像をプレビューできるように表示させる
display_btn.addEventListener("click", function () {
  if (greetingMessage.length > 0) {
    (0,_module_component_broadcastMessageOperations_js__WEBPACK_IMPORTED_MODULE_0__.displayMessageToList)(greetingMessage, null, null, "js_accordion_wrapper_greeting", "accordion_greeting");
    (0,_module_component_broadcastMessageOperations_js__WEBPACK_IMPORTED_MODULE_0__.deleteList)("accordion_greeting");
    (0,_module_component_broadcastMessageOperations_js__WEBPACK_IMPORTED_MODULE_0__.dragAndDrop)("accordion_greeting");
    greetingMessage = "";
    greetingMessageInput.value = "";
  }
});

// ドラッグドロップ機能
window.onload = function (e) {
  (0,_module_component_broadcastMessageOperations_js__WEBPACK_IMPORTED_MODULE_0__.dragAndDrop)("accordion_greeting");
};
var fileStorage = {}; // Fileオブジェクトを保存するためのオブジェクト
var uploads = document.querySelectorAll(".js_upload");
uploads.forEach(function (upload, index) {
  upload.addEventListener("change", /*#__PURE__*/function () {
    var _ref = _asyncToGenerator(/*#__PURE__*/_regeneratorRuntime().mark(function _callee(e) {
      var file, objectURL, resizedBlob;
      return _regeneratorRuntime().wrap(function _callee$(_context) {
        while (1) switch (_context.prev = _context.next) {
          case 0:
            (0,_module_component_broadcastMessageOperations_js__WEBPACK_IMPORTED_MODULE_0__.hideErrorMsg)();
            // 選択されたファイルにアクセス
            file = e.target.files[0]; // FileオブジェクトのままURLを作成
            objectURL = URL.createObjectURL(file); // `displayMessageToList` にファイルのURLを渡して表示する
            (0,_module_component_broadcastMessageOperations_js__WEBPACK_IMPORTED_MODULE_0__.displayMessageToList)(null, objectURL, index, "js_accordion_wrapper_greeting", "accordion_greeting");
            (0,_module_component_broadcastMessageOperations_js__WEBPACK_IMPORTED_MODULE_0__.deleteList)("accordion_greeting", upload);
            // ファイルをリサイズし、Blobオブジェクトを取得
            _context.next = 7;
            return (0,_module_util_processAndResizeImage_js__WEBPACK_IMPORTED_MODULE_4__.resizeImage)(file);
          case 7:
            resizedBlob = _context.sent;
            // リサイズされたBlobオブジェクトを保存
            fileStorage[index] = resizedBlob;
            // 使用後にメモリ解放
            // URL.revokeObjectURL(objectURL);

            // ドラッグ＆ドロップの初期化
            (0,_module_component_broadcastMessageOperations_js__WEBPACK_IMPORTED_MODULE_0__.dragAndDrop)("accordion_greeting");
          case 10:
          case "end":
            return _context.stop();
        }
      }, _callee);
    }));
    return function (_x) {
      return _ref.apply(this, arguments);
    };
  }());
});

// 一斉送信の送信ボタンクリック処理

var submit_btn = document.querySelector(".js_greeting_submit_btn");
submit_btn.addEventListener("click", function () {
  if ((0,_module_component_broadcastMessageOperations_js__WEBPACK_IMPORTED_MODULE_0__.hasValue)("accordion_greeting")) {
    var data = document.querySelectorAll(".js_data");
    var sendingData = {
      content: [],
      admin_id: document.getElementById("js_greeting_account_id").value
    };
    data.forEach(function (data) {
      if (data.querySelector(".js_img")) {
        var fileIndex = data.querySelector(".js_img").getAttribute("data-file-index");
        sendingData.content.push({
          data: fileStorage[fileIndex],
          type: "greeting_img"
        });
      } else {
        sendingData.content.push({
          data: (0,_module_util_messageService_js__WEBPACK_IMPORTED_MODULE_3__.cleanHtmlContent)(data.innerHTML),
          type: "greeting_text"
        });
      }
    });

    // バックエンドに送信するデータ

    var loader = document.querySelector(".loader");
    modal.classList.add("hidden");
    (0,_module_component_modalOperation_js__WEBPACK_IMPORTED_MODULE_1__.open_modal)(loader);
    console.log(sendingData);
    (0,_module_util_fetch_js__WEBPACK_IMPORTED_MODULE_2__.fetchPostOperation)(sendingData, "/api/greeting_message/store").then(function (res) {
      if (res["status"] = "success") {
        document.getElementById("js_greeting_modal").classList.add("hidden");
        document.querySelector(".bg").classList.add("hidden");
        loader.classList.add("hidden");

        //成功メッセージを出す処理
        var success_el = document.getElementById("js_alert_success");
        success_el.style.display = "block";
        success_el.innerHTML = "初回あいさつメッセージの登録に成功しました";
        document.querySelector(".js_greeting_input").value = "";
        document.querySelector(".js_upload").value = "";
        document.querySelector(".js_accordion_wrapper_greeting").innerHTML = "";

        // 成功メッセージを出して2秒後に批評にする
        setTimeout(function () {
          success_el.style.display = "none";
        }, 2000);
      } else {
        //成功メッセージを出す処理
        var _success_el = document.getElementById("js_alert_success");
        _success_el.style.display = "block";
        _success_el.innerHTML = "初回あいさつメッセージに失敗しました。再度お試しください。";
        document.querySelector(".js_greeting_input").value = "";
        document.querySelector(".js_upload").value = "";
        document.querySelector(".js_accordion_wrapper_greeting").innerHTML = "";
      }
    });
  } else {
    var error_el = document.querySelector(".js_broadcast_error");
    error_el.classList.remove("hidden");
  }
});
})();

/******/ })()
;