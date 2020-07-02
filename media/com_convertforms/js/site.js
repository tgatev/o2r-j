"undefined" == typeof Joomla && (
    // Some check ?
    Joomla = {}, function (c, u) {
    "use strict";
    c.Text = {
        strings: {}, _: function (e, t) {
            var r = c.getOptions("joomla.jtext");
            return r && (this.load(r), c.loadOptions({"joomla.jtext": null})), t = void 0 === t ? "" : t, e = e.toUpperCase(), void 0 !== this.strings[e] ? this.strings[e] : t
        }, load: function (e) {
            for (var t in e) e.hasOwnProperty(t) && (this.strings[t.toUpperCase()] = e[t]);
            return this
        }
    }, c.JText = c.Text, c.optionsStorage = c.optionsStorage || null, c.getOptions = function (e, t) {
        return c.optionsStorage || c.loadOptions(), void 0 !== c.optionsStorage[e] ? c.optionsStorage[e] : t
    }, c.loadOptions = function (e) {
        if (!e) {
            for (var t, r, o, n = u.querySelectorAll(".joomla-script-options.new"), a = 0, i = 0, s = n.length; i < s; i++) t = (r = n[i]).text || r.textContent, (o = JSON.parse(t)) && (c.loadOptions(o), a++), r.className = r.className.replace(" new", " loaded");
            if (a) return
        }
        if (c.optionsStorage) {
            if (e) for (var l in e) e.hasOwnProperty(l) && (c.optionsStorage[l] = e[l])
        } else c.optionsStorage = e || {}
    }
}(Joomla, document)
),
// Statement
    function (o, n) {
    "use strict";
    var a = {
        getCSRFToken: function () {
            var e = Joomla.getOptions("csrf.token");
            return e || ConvertFormsConfig.token
        },
        getBaseURL: function () {
            var e = Joomla.getOptions("system.paths");
            return e ? e.root + "/" : o.location.pathname
        },
        getFormData: function (e) {
            return new FormData(e)
        },
        serializeFormData: function (e, t) {
            t = t || !1;
            var r = [], o = e.entries(), n = Array.isArray(o), a = 0;
            for (o = n ? o : o[Symbol.iterator](); ;) {
                var i;
                if (n) {
                    if (a >= o.length) break;
                    i = o[a++]
                } else {
                    if ((a = o.next()).done) break;
                    i = a.value
                }
                var s = i, l = s[0], c = s[1];
                if (t && -1 < l.indexOf("cf[")) {
                    var u = -1 < l.indexOf("[]");
                    l = l.match(/cf\[(.*?)\]/)[1], u && (l += "[]")
                }
                r.push(encodeURIComponent(l) + "=" + encodeURIComponent(c))
            }
            return r.join("&")
        },
        isInViewport: function (e) {
            var t = e.getBoundingClientRect();
            return 0 <= t.top && 0 <= t.left && t.bottom <= (o.innerHeight || n.documentElement.clientHeight) && t.right <= (o.innerWidth || n.documentElement.clientWidth)
        },
        emitEvent: function (e, t, r) {
            var o = new CustomEvent(e, {detail: r, cancelable: !0});
            return t.dispatchEvent(o), o
        },
        request: function (t) {
            t = a.extend({
                url: "",
                method: "POST",
                headers: null,
                data: null,
                onSuccess: null,
                onError: null,
                onFail: null
            }, t);
            try {
                var r = new XMLHttpRequest;
                if (r.open(t.method, t.url, !0), r.setRequestHeader("X-Requested-With", "XMLHttpRequest"), r.setRequestHeader("X-Ajax-Engine", "Convert Forms!"), t.headers) for (var e in t.headers) t.headers.hasOwnProperty(e) && r.setRequestHeader(e, t.headers[e]);
                if (r.onload = function () {
                    4 === r.readyState && (200 === r.status ? t.onSuccess && t.onSuccess.call(o, r.responseText, r) : t.onError && t.onError.call(o, r.responseText, r), t.onComplete && t.onComplete.call(o, r))
                }, t.onBefore && !1 === t.onBefore.call(o, r)) return r;
                r.send(t.data)
            } catch (e) {
                return t.onFail && t.onFail.call(o, e, r), console.error(e), !1
            }
            return r
        },
        extend: function (e, t) {
            for (var r in t) t.hasOwnProperty(r) && (e[r] = t[r]);
            return e
        },
        capitalize: function (e) {
            return "string" != typeof e ? "" : e.charAt(0).toUpperCase() + e.slice(1)
        },
        browserIsIE: function () {
            var e = o.navigator.userAgent, t = e.indexOf("MSIE "), r = e.indexOf("Trident/");
            return 0 < t || 0 < r
        },
        loadScript: function (e, t) {
            var r = n.head, o = n.createElement("script");
            o.type = "text/javascript", o.src = e, o.onreadystatechange = t, o.onload = t, r.appendChild(o)
        },
        onReady: function (e) {
            "loading" == n.readyState ? n.addEventListener("DOMContentLoaded", e) : e()
        }
    };

    o.ConvertForms = o.ConvertForms || [], o.ConvertForms.Helper = a
}(window, document), function (l, c) {
    "use strict";
    var o = function (a) {
        var i = {selector: a}, s = l.ConvertForms.Helper, n = a.querySelector("form"), t = {
            INVALID_RESPONSE: "Invalid Response",
            INVALID_TASK: "Invalid Task",
            ERROR_WAIT_FILE_UPLOADS: "Please wait file uploading to complete.",
            ERROR_INPUTMASK_INCOMPLETE: "Mask is incomplete",
            SUBMISSION_CANCELLED: "Submission Cancelled"
        };
        i.submit = function () {

            var e = i.emitEvent("beforeSubmit");
            e.defaultPrevented ? e.detail.hasOwnProperty("error") && i.throwError(e.detail.error) : s.request({
                url: s.getBaseURL() + "?option=com_convertforms&task=submit",
                headers: {"X-CSRF-Token": s.getCSRFToken()},
                data: s.getFormData(n),
                onBefore: function () {
                    a.classList.add("cf-working"), a.classList.add("cf-disabled"), a.classList.remove("cf-error"), a.classList.remove("cf-success"), a.querySelector(".cf-response").removeAttribute("role")
                },
                onSuccess: function (t, e) {
                    try {
                        t = JSON.parse(t)
                    } catch (e) {
                        var r = t.match('{"convertforms"(.*?)}$');
                        if (null == r) return void i.throwError(i.text("INVALID_RESPONSE") + ": " + t);
                        t = JSON.parse(r[0])
                    }
                    if (t.success) {
                        i.emitEvent("success", {response: t, xhr: e});
                        var o = "formTask" + s.capitalize(t.task);
                        if (!i.hasOwnProperty(o)) return void i.throwError(i.text("INVALID_TASK") + ": " + o);
                        i[o].call(l, t), i.emitEvent("afterTask", {response: t, xhr: e, task: t.task})
                    } else {
                        i.emitEvent("error", {response: t, xhr: e}), i.throwError(t.error);
                        var n = a.querySelector(".cf-fields .cf-input");
                        n && n.focus()
                    }
                },
                onComplete: function (e) {
                    i.emitEvent("afterSubmit", {xhr: e}), a.classList.remove("cf-working"), a.classList.remove("cf-disabled");
                    a.querySelector("form").reportValidity()
                    var t = a.querySelector(".cf-response");
                    s.isInViewport(t) || t.scrollIntoView()

                },
                onError: function (e, t) {
                    i.throwError("Error: " + e)
                },
                onFail: function (e, t) {
                    i.throwError("Fail: " + e)
                }
            })
        }, i.emitEvent = function (e, t, r) {
            if (e) {
                // TODO // Dump element for debuging
                // console.log(e);
                // alert(e);
                if ((t = t || {}).instance = i, r = r || !0) {
                    var o = "ConvertForms" + s.capitalize(e);
                    s.emitEvent(o, c, t)
                }
                return s.emitEvent(e, a, t)
            }
        }, i.formTaskMsg = function (e) {
            a.classList.add("cf-success"), a.querySelector(".cf-response").innerHTML = e.value, "1" == e.resetform && n.reset()
        }, i.formTaskUrl = function (e) {
            var t = e.value;
            if ("1" == e.passdata) {
                var r = s.getFormData(n), o = s.serializeFormData(r, !0);
                t += (-1 < e.value.indexOf("?") ? "&" : "?") + o
            }
            l.location.href = t
        }, i.throwError = function (e) {
            if(e === 'Enter your email: Invalid email address' ){
                // setCustomValidity
                let input = a.querySelector('input[type="email"]');
                input.setCustomValidity(e);
                input.oninput = function (e) {
                    e.target.setCustomValidity("");
                };
                a.querySelector(".cf-response").setAttribute("role", "alert")
                a.querySelector(".cf-response").style.display = "none";
            }
            a.classList.add("cf-error");
            var t = a.querySelector(".cf-response");
            t.innerHTML = e, t.setAttribute("role", "alert")

        }, i.text = function (e) {
            return t[e]
        };
        return a.classList.contains("cf-init") || (l.IntersectionObserver && new IntersectionObserver(function (e, t) {
            e.forEach(function (e) {
                e.isIntersecting && (i.emitEvent("impression", {entry: e}), t.unobserve(e.target))
            })
        },
            {rootMargin: "0px 0px 0px 0px"}).observe(a),
            n.addEventListener("submit", function (e) {
            e.preventDefault(), i.submit(e)
        }), a.classList.add("cf-init")), a.ConvertForms = i
    };
    l.ConvertForms = l.ConvertForms || [];
    var n = l.ConvertForms.Helper;

    function e() {
        for (var e = c.querySelectorAll(".convertforms"), t = 0; t < e.length; t++) {
            var r = e[t];
            o(r)
        }
        n.emitEvent("ConvertFormsInit", c, e)
    }

    n.onReady(function () {
        n.browserIsIE() ? n.loadScript(n.getBaseURL() + "media/com_convertforms/js/polyfills.js", e) : e()
    })
}(window, document), document.addEventListener("ConvertFormsInit", function (e) {
    if ("undefined" != typeof Dropzone) {
        var t = e.detail,
            c = ConvertForms.Helper.getBaseURL() + "?option=com_ajax&format=raw&plugin=convertforms&task=field&field_type=fileupload",
            u = ConvertForms.Helper.getCSRFToken();
        Dropzone.autoDiscover = !1, Dropzone.prototype.defaultOptions.dictFallbackMessage = Joomla.JText._("COM_CONVERTFORMS_UPLOAD_FALLBACK_MESSAGE"), Dropzone.prototype.defaultOptions.dictFileTooBig = Joomla.JText._("COM_CONVERTFORMS_UPLOAD_FILETOOBIG"), Dropzone.prototype.defaultOptions.dictInvalidFileType = Joomla.JText._("COM_CONVERTFORMS_UPLOAD_INVALID_FILE"), Dropzone.prototype.defaultOptions.dictResponseError = Joomla.JText._("COM_CONVERTFORMS_UPLOAD_RESPONSE_ERROR"), Dropzone.prototype.defaultOptions.dictCancelUpload = Joomla.JText._("COM_CONVERTFORMS_UPLOAD_CANCEL_UPLOAD"), Dropzone.prototype.defaultOptions.dictCancelUploadConfirmation = Joomla.JText._("COM_CONVERTFORMS_UPLOAD_CANCEL_UPLOAD_CONFIRMATION"), Dropzone.prototype.defaultOptions.dictRemoveFile = Joomla.JText._("COM_CONVERTFORMS_UPLOAD_REMOVE_FILE"), Dropzone.prototype.defaultOptions.dictMaxFilesExceeded = Joomla.JText._("COM_CONVERTFORMS_UPLOAD_MAX_FILES_EXCEEDED"), t.forEach(function (s) {
            var e = s.querySelectorAll(".cfupload"), l = [];
            e.forEach(function (n) {
                var e = n.closest(".cf-control-input").querySelector(".cfup-tmpl"), t = e.innerHTML;
                e.closest(".cf-control-input").removeChild(e);
                var r = parseFloat(n.getAttribute("data-maxfilesize"));
                r = r || null;
                var o = parseInt(n.getAttribute("data-maxfiles"));
                o = o || null;
                var a = s.querySelector("button.cf-btn"), i = new Dropzone(n, {
                    url: c,
                    previewTemplate: t,
                    maxFilesize: r,
                    uploadMultiple: 1 != o,
                    maxFiles: o,
                    acceptedFiles: n.getAttribute("data-acceptedfiles"),
                    autoProcessQueue: !0,
                    parallelUploads: 1,
                    filesizeBase: 1024,
                    createImageThumbnails: !1,
                    timeout: 3e5
                });
                a && (i.on("queuecomplete", function () {
                    a.classList.remove("cf-disabled")
                }), i.on("processing", function () {
                    a.classList.add("cf-disabled")
                })), i.on("sending", function (e, t, r) {
                    r.append("form_id", n.closest("form").querySelector("input[name='cf[form_id]']").value), r.append("field_key", n.getAttribute("data-key")), t.setRequestHeader("X-CSRF-Token", u), r.append(u, 1)
                }), i.on("success", function (e) {
                    var t = e.xhr.response;
                    try {
                        t = JSON.parse(t)
                    } catch (e) {
                        var r = t.match(/{([^}]*)}/i);
                        null !== r ? t = JSON.parse(r[0]) : alert("Error! " + e + "<br>" + t)
                    }
                    var o = document.createElement("input");
                    o.setAttribute("type", "hidden"), o.setAttribute("name", n.dataset.name), o.setAttribute("value", t.file), e.previewTemplate.appendChild(o)
                }), l.push(i)
            }), s.addEventListener("beforeSubmit", function (e) {
                e.defaultPrevented || !function () {
                    var o = 0;
                    return l.forEach(function (e) {
                        var t = e.getQueuedFiles().length, r = e.getUploadingFiles().length;
                        o = o + t + r
                    }), 0 < o
                }() || (e.preventDefault(), e.detail.error = e.detail.instance.text("ERROR_WAIT_FILE_UPLOADS"))
            }), s.addEventListener("success", function () {
                l.forEach(function (e) {
                    e.removeAllFiles()
                })
            })
        })
    }
}), document.addEventListener("ConvertFormsInit", function (e) {
    "undefined" != typeof Inputmask && e.detail.forEach(function (e) {
        var o = e.querySelectorAll(".cf-input[data-inputmask-mask]");
        o && (Inputmask("", {
            jitMasking: !1,
            showMaskOnHover: !1
        }).mask(o), e.addEventListener("beforeSubmit", function (e) {
            if (!e.defaultPrevented) for (var t = 0; t < o.length; t++) {
                var r = o[t];
                if (!r.hasAttribute("required")) return;
                if (!r.inputmask.isComplete()) {
                    e.preventDefault(), e.detail.error = e.detail.instance.text("ERROR_INPUTMASK_INCOMPLETE"), r.focus();
                    break
                }
            }
        }))
    })
});
