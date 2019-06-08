
var jscolor = {
    dir: "",
    bindClass: "colorPicker",
    binding: true,
    preloading: true,
    install: function() {
        jscolor.addEvent(window, "load", jscolor.init)
    },
    init: function() {
        if (jscolor.binding) {
            jscolor.bind()
        }
        if (jscolor.preloading) {
            jscolor.preload()
        }
    },
    getDir: function() {
        if (!jscolor.dir) {
            var a = jscolor.detectDir();
            jscolor.dir = a !== false ? a: ""
        }
        return jscolor.dir
    },
    detectDir: function() {
        var c = location.href;
        var d = document.getElementsByTagName("base");
        for (var a = 0; a < d.length; a += 1) {
            if (d[a].href) {
                c = d[a].href
            }
        }
        var d = document.getElementsByTagName("script");
        for (var a = 0; a < d.length; a += 1) {
            if (d[a].src && /(^|\/)jscolor\.js([?#].*)?$/i.test(d[a].src)) {
                var f = new jscolor.URI(d[a].src);
                var b = f.toAbsolute(c);
                b.path = b.path.replace(/[^\/]+$/, "");
                b.query = null;
                b.fragment = null;
                return b.toString()
            }
        }
        return false
    },
    bind: function() {
        var matchClass = new RegExp("(^|\\s)(" + jscolor.bindClass + ")\\s*(\\{[^}]*\\})?", "i");
        var e = document.getElementsByTagName("input");
        for (var i = 0; i < e.length; i += 1) {
            var m;
            if (!e[i].color && e[i].className && (m = e[i].className.match(matchClass))) {
                var prop = {};
                if (m[3]) {
                    try {
                        eval("prop=" + m[3])
                    } catch(eInvalidProp) {}
                }
                e[i].color = new jscolor.color(e[i], prop)
            }
        }
    },
    preload: function() {
        for (var a in jscolor.imgRequire) {
            if (jscolor.imgRequire.hasOwnProperty(a)) {
                jscolor.loadImage(a)
            }
        }
    },
    images: {
        pad: [181, 101],
        sld: [16, 101],
        cross: [15, 15],
        arrow: [7, 11]
    },
    imgRequire: {},
    imgLoaded: {},
    requireImage: function(a) {
        jscolor.imgRequire[a] = true
    },
    loadImage: function(a) {
        if (!jscolor.imgLoaded[a]) {
            jscolor.imgLoaded[a] = new Image();
            jscolor.imgLoaded[a].src = jscolor.getDir() + a
        }
    },
    fetchElement: function(a) {
        return typeof a === "string" ? document.getElementById(a) : a
    },
    addEvent: function(a, c, b) {
        if (a.addEventListener) {
            a.addEventListener(c, b, false)
        } else {
            if (a.attachEvent) {
                a.attachEvent("on" + c, b)
            }
        }
    },
    fireEvent: function(a, c) {
        if (!a) {
            return
        }
        if (document.createEventObject) {
            var b = document.createEventObject();
            a.fireEvent("on" + c, b)
        } else {
            if (document.createEvent) {
                var b = document.createEvent("HTMLEvents");
                b.initEvent(c, true, true);
                a.dispatchEvent(b)
            } else {
                if (a["on" + c]) {
                    a["on" + c]()
                }
            }
        }
    },
    getElementPos: function(c) {
        var d = c,
        b = c;
        var a = 0,
        f = 0;
        if (d.offsetParent) {
            do {
                a += d.offsetLeft;
                f += d.offsetTop
            }
            while (d = d.offsetParent)
        }
        while ((b = b.parentNode) && b.nodeName.toUpperCase() !== "BODY") {
            a -= b.scrollLeft;
            f -= b.scrollTop
        }
        return [a, f]
    },
    getElementSize: function(a) {
        return [a.offsetWidth, a.offsetHeight]
    },
    getMousePos: function(a) {
        if (!a) {
            a = window.event
        }
        if (typeof a.pageX === "number") {
            return [a.pageX, a.pageY]
        } else {
            if (typeof a.clientX === "number") {
                return [a.clientX + document.body.scrollLeft + document.documentElement.scrollLeft, a.clientY + document.body.scrollTop + document.documentElement.scrollTop]
            }
        }
    },
    getViewPos: function() {
        if (typeof window.pageYOffset === "number") {
            return [window.pageXOffset, window.pageYOffset]
        } else {
            if (document.body && (document.body.scrollLeft || document.body.scrollTop)) {
                return [document.body.scrollLeft, document.body.scrollTop]
            } else {
                if (document.documentElement && (document.documentElement.scrollLeft || document.documentElement.scrollTop)) {
                    return [document.documentElement.scrollLeft, document.documentElement.scrollTop]
                } else {
                    return [0, 0]
                }
            }
        }
    },
    getViewSize: function() {
        if (typeof window.innerWidth === "number") {
            return [window.innerWidth, window.innerHeight]
        } else {
            if (document.body && (document.body.clientWidth || document.body.clientHeight)) {
                return [document.body.clientWidth, document.body.clientHeight]
            } else {
                if (document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)) {
                    return [document.documentElement.clientWidth, document.documentElement.clientHeight]
                } else {
                    return [0, 0]
                }
            }
        }
    },
    URI: function(a) {
        this.scheme = null;
        this.authority = null;
        this.path = "";
        this.query = null;
        this.fragment = null;
        this.parse = function(d) {
            var c = d.match(/^(([A-Za-z][0-9A-Za-z+.-]*)(:))?((\/\/)([^\/?#]*))?([^?#]*)((\?)([^#]*))?((#)(.*))?/);
            this.scheme = c[3] ? c[2] : null;
            this.authority = c[5] ? c[6] : null;
            this.path = c[7];
            this.query = c[9] ? c[10] : null;
            this.fragment = c[12] ? c[13] : null;
            return this
        };
        this.toString = function() {
            var c = "";
            if (this.scheme !== null) {
                c = c + this.scheme + ":"
            }
            if (this.authority !== null) {
                c = c + "//" + this.authority
            }
            if (this.path !== null) {
                c = c + this.path
            }
            if (this.query !== null) {
                c = c + "?" + this.query
            }
            if (this.fragment !== null) {
                c = c + "#" + this.fragment
            }
            return c
        };
        this.toAbsolute = function(e) {
            var e = new jscolor.URI(e);
            var d = this;
            var c = new jscolor.URI;
            if (e.scheme === null) {
                return false
            }
            if (d.scheme !== null && d.scheme.toLowerCase() === e.scheme.toLowerCase()) {
                d.scheme = null
            }
            if (d.scheme !== null) {
                c.scheme = d.scheme;
                c.authority = d.authority;
                c.path = b(d.path);
                c.query = d.query
            } else {
                if (d.authority !== null) {
                    c.authority = d.authority;
                    c.path = b(d.path);
                    c.query = d.query
                } else {
                    if (d.path === "") {
                        c.path = e.path;
                        if (d.query !== null) {
                            c.query = d.query
                        } else {
                            c.query = e.query
                        }
                    } else {
                        if (d.path.substr(0, 1) === "/") {
                            c.path = b(d.path)
                        } else {
                            if (e.authority !== null && e.path === "") {
                                c.path = "/" + d.path
                            } else {
                                c.path = e.path.replace(/[^\/]+$/, "") + d.path
                            }
                            c.path = b(c.path)
                        }
                        c.query = d.query
                    }
                    c.authority = e.authority
                }
                c.scheme = e.scheme
            }
            c.fragment = d.fragment;
            return c
        };
        function b(e) {
            var c = "";
            while (e) {
                if (e.substr(0, 3) === "../" || e.substr(0, 2) === "./") {
                    e = e.replace(/^\.+/, "").substr(1)
                } else {
                    if (e.substr(0, 3) === "/./" || e === "/.") {
                        e = "/" + e.substr(3)
                    } else {
                        if (e.substr(0, 4) === "/../" || e === "/..") {
                            e = "/" + e.substr(4);
                            c = c.replace(/\/?[^\/]*$/, "")
                        } else {
                            if (e === "." || e === "..") {
                                e = ""
                            } else {
                                var d = e.match(/^\/?[^\/]*/)[0];
                                e = e.substr(d.length);
                                c = c + d
                            }
                        }
                    }
                }
            }
            return c
        }
        if (a) {
            this.parse(a)
        }
    },
    color: function(A, d) {
        this.required = true;
        this.adjust = true;
        this.hash = true;
        this.caps = true;
        this.valueElement = A;
        this.styleElement = A;
        this.hsv = [0, 0, 1];
        this.rgb = [1, 1, 1];
        this.pickerOnfocus = true;
        this.pickerMode = "HSV";
        this.pickerPosition = "bottom";
        this.pickerFace = 10;
        this.pickerFaceColor = "ThreeDFace";
        this.pickerBorder = 1;
        this.pickerBorderColor = "ThreeDHighlight ThreeDShadow ThreeDShadow ThreeDHighlight";
        this.pickerInset = 1;
        this.pickerInsetColor = "ThreeDShadow ThreeDHighlight ThreeDHighlight ThreeDShadow";
        this.pickerZIndex = 10000;
        for (var r in d) {
            if (d.hasOwnProperty(r)) {
                this[r] = d[r]
            }
        }
        this.hidePicker = function() {
            if (s()) {
                f()
            }
        };
        this.showPicker = function() {
            if (!s()) {
                var J = jscolor.getElementPos(A);
                var G = jscolor.getElementSize(A);
                var D = jscolor.getViewPos();
                var L = jscolor.getViewSize();
                var p = [2 * this.pickerBorder + 4 * this.pickerInset + 2 * this.pickerFace + jscolor.images.pad[0] + 2 * jscolor.images.arrow[0] + jscolor.images.sld[0], 2 * this.pickerBorder + 2 * this.pickerInset + 2 * this.pickerFace + jscolor.images.pad[1]];
                var K,
                I,
                H;
                switch (this.pickerPosition.toLowerCase()) {
                case "left":
                    K = 1;
                    I = 0;
                    H = -1;
                    break;
                case "right":
                    K = 1;
                    I = 0;
                    H = 1;
                    break;
                case "top":
                    K = 0;
                    I = 1;
                    H = -1;
                    break;
                default:
                    K = 0;
                    I = 1;
                    H = 1;
                    break
                }
                var F = (G[I] + p[I]) / 2;
                var E = [ - D[K] + J[K] + p[K] > L[K] ? ( - D[K] + J[K] + G[K] / 2 > L[K] / 2 && J[K] + G[K] - p[K] >= 0 ? J[K] + G[K] - p[K] : J[K]) : J[K], -D[I] + J[I] + G[I] + p[I] - F + F * H > L[I] ? ( - D[I] + J[I] + G[I] / 2 > L[I] / 2 && J[I] + G[I] - F - F * H >= 0 ? J[I] + G[I] - F - F * H: J[I] + G[I] - F + F * H) : (J[I] + G[I] - F + F * H >= 0 ? J[I] + G[I] - F + F * H: J[I] + G[I] - F - F * H)];
                i(E[K], E[I])
            }
        };
        this.importColor = function() {
            if (!a) {
                this.exportColor()
            } else {
                if (!this.adjust) {
                    if (!this.fromString(a.value, v)) {
                        C.style.backgroundColor = C.jscStyle.backgroundColor;
                        C.style.color = C.jscStyle.color;
                        this.exportColor(v | B)
                    }
                } else {
                    if (!this.required && /^\s*$/.test(a.value)) {
                        a.value = "";
                        C.style.backgroundColor = C.jscStyle.backgroundColor;
                        C.style.color = C.jscStyle.color;
                        this.exportColor(v | B)
                    } else {
                        if (this.fromString(a.value)) {} else {
                            this.exportColor()
                        }
                    }
                }
            }
        };
        this.exportColor = function(p) {
            if (! (p & v) && a) {
                var D = this.toString();
                if (this.caps) {
                    D = D.toUpperCase()
                }
                if (this.hash) {
                    D = "#" + D
                }
                a.value = D
            }
            if (! (p & B) && C) {
                C.style.backgroundColor = "#" + this.toString();
                C.style.color = 0.213 * this.rgb[0] + 0.715 * this.rgb[1] + 0.072 * this.rgb[2] < 0.5 ? "#FFF": "#000"
            }
            if (! (p & t) && s()) {
                q()
            }
            if (! (p & e) && s()) {
                z()
            }
        };
        this.fromHSV = function(F, E, D, p) {
            F < 0 && (F = 0) || F > 6 && (F = 6);
            E < 0 && (E = 0) || E > 1 && (E = 1);
            D < 0 && (D = 0) || D > 1 && (D = 1);
            this.rgb = g(F === null ? this.hsv[0] : (this.hsv[0] = F), E === null ? this.hsv[1] : (this.hsv[1] = E), D === null ? this.hsv[2] : (this.hsv[2] = D));
            this.exportColor(p)
        };
        this.fromRGB = function(G, F, p, D) {
            G < 0 && (G = 0) || G > 1 && (G = 1);
            F < 0 && (F = 0) || F > 1 && (F = 1);
            p < 0 && (p = 0) || p > 1 && (p = 1);
            var E = w(G === null ? this.rgb[0] : (this.rgb[0] = G), F === null ? this.rgb[1] : (this.rgb[1] = F), p === null ? this.rgb[2] : (this.rgb[2] = p));
            if (E[0] !== null) {
                this.hsv[0] = E[0]
            }
            if (E[2] !== 0) {
                this.hsv[1] = E[1]
            }
            this.hsv[2] = E[2];
            this.exportColor(D)
        };
        this.fromString = function(E, D) {
            var p = E.match(/^\W*([0-9A-F]{3}([0-9A-F]{3})?)\W*$/i);
            if (!p) {
                return false
            } else {
                if (p[1].length === 6) {
                    this.fromRGB(parseInt(p[1].substr(0, 2), 16) / 255, parseInt(p[1].substr(2, 2), 16) / 255, parseInt(p[1].substr(4, 2), 16) / 255, D)
                } else {
                    this.fromRGB(parseInt(p[1].charAt(0) + p[1].charAt(0), 16) / 255, parseInt(p[1].charAt(1) + p[1].charAt(1), 16) / 255, parseInt(p[1].charAt(2) + p[1].charAt(2), 16) / 255, D)
                }
                return true
            }
        };
        this.toString = function() {
            return ((256 | Math.round(255 * this.rgb[0])).toString(16).substr(1) + (256 | Math.round(255 * this.rgb[1])).toString(16).substr(1) + (256 | Math.round(255 * this.rgb[2])).toString(16).substr(1))
        };
        function w(H, G, D) {
            var I = Math.min(Math.min(H, G), D);
            var E = Math.max(Math.max(H, G), D);
            var p = E - I;
            if (p === 0) {
                return [null, 0, E]
            }
            var F = H === I ? 3 + (D - G) / p: (G === I ? 5 + (H - D) / p: 1 + (G - H) / p);
            return [F === 6 ? 0: F, p / E, E]
        }
        function g(G, F, D) {
            if (G === null) {
                return [D, D, D]
            }
            var E = Math.floor(G);
            var H = E % 2 ? G - E: 1 - (G - E);
            var p = D * (1 - F);
            var I = D * (1 - F * H);
            switch (E) {
            case 6:
            case 0:
                return [D, I, p];
            case 1:
                return [I, D, p];
            case 2:
                return [p, D, I];
            case 3:
                return [p, I, D];
            case 4:
                return [I, p, D];
            case 5:
                return [D, p, I]
            }
        }
        function f() {
            delete jscolor.picker.owner;
            document.getElementsByTagName("body")[0].removeChild(jscolor.picker.boxB)
        }
        function i(D, K) {
            if (!jscolor.picker) {
                jscolor.picker = {
                    box: document.createElement("div"),
                    boxB: document.createElement("div"),
                    pad: document.createElement("div"),
                    padB: document.createElement("div"),
                    padM: document.createElement("div"),
                    sld: document.createElement("div"),
                    sldB: document.createElement("div"),
                    sldM: document.createElement("div")
                };
                for (var G = 0, J = 4; G < jscolor.images.sld[1]; G += J) {
                    var E = document.createElement("div");
                    E.style.height = J + "px";
                    E.style.fontSize = "1px";
                    E.style.lineHeight = "0";
                    jscolor.picker.sld.appendChild(E)
                }
                jscolor.picker.sldB.appendChild(jscolor.picker.sld);
                jscolor.picker.box.appendChild(jscolor.picker.sldB);
                jscolor.picker.box.appendChild(jscolor.picker.sldM);
                jscolor.picker.padB.appendChild(jscolor.picker.pad);
                jscolor.picker.box.appendChild(jscolor.picker.padB);
                jscolor.picker.box.appendChild(jscolor.picker.padM);
                jscolor.picker.boxB.appendChild(jscolor.picker.box)
            }
            var I = jscolor.picker;
            n = [D + j.pickerBorder + j.pickerFace + j.pickerInset, K + j.pickerBorder + j.pickerFace + j.pickerInset];
            x = [null, K + j.pickerBorder + j.pickerFace + j.pickerInset];
            I.box.onmouseup = I.box.onmouseout = function() {
                A.focus()
            };
            I.box.onmousedown = function() {
                l = true
            };
            I.box.onmousemove = function(p) {
				try{
					if(document.selection){
							if(document.selection.empty){
								document.selection.empty();
							}else{
								document.selection = null;
							}
					}else if(window.getSelection){
							window.getSelection().removeAllRanges();
					}
				}catch(e){}				
                c && u(p);
                m && h(p)
            };
            I.padM.onmouseup = I.padM.onmouseout = function() {
                if (c) {
                    c = false;
                    jscolor.fireEvent(a, "change")
                }
            };
            I.padM.onmousedown = function(p) {
                c = true;
                u(p)
            };
            I.sldM.onmouseup = I.sldM.onmouseout = function() {
                if (m) {
                    m = false;
                    jscolor.fireEvent(a, "change")
                }
            };
            I.sldM.onmousedown = function(p) {
                m = true;
                h(p)
            };
            I.box.style.width = 4 * j.pickerInset + 2 * j.pickerFace + jscolor.images.pad[0] + 2 * jscolor.images.arrow[0] + jscolor.images.sld[0] + "px";
            I.box.style.height = 2 * j.pickerInset + 2 * j.pickerFace + jscolor.images.pad[1] + "px";
            I.boxB.style.position = "absolute";
            I.boxB.style.clear = "both";
            I.boxB.style.left = D + "px";
            I.boxB.style.top = K + "px";
            I.boxB.style.zIndex = j.pickerZIndex;
            I.boxB.style.border = j.pickerBorder + "px solid";
            I.boxB.style.borderColor = j.pickerBorderColor;
            I.boxB.style.background = j.pickerFaceColor;
            I.pad.style.width = jscolor.images.pad[0] + "px";
            I.pad.style.height = jscolor.images.pad[1] + "px";
            I.padB.style.position = "absolute";
            I.padB.style.left = j.pickerFace + "px";
            I.padB.style.top = j.pickerFace + "px";
            I.padB.style.border = j.pickerInset + "px solid";
            I.padB.style.borderColor = j.pickerInsetColor;
            I.padM.style.position = "absolute";
            I.padM.style.left = "0";
            I.padM.style.top = "0";
            I.padM.style.width = j.pickerFace + 2 * j.pickerInset + jscolor.images.pad[0] + jscolor.images.arrow[0] + "px";
            I.padM.style.height = I.box.style.height;
            I.padM.style.cursor = "crosshair";
            I.sld.style.overflow = "hidden";
            I.sld.style.width = jscolor.images.sld[0] + "px";
            I.sld.style.height = jscolor.images.sld[1] + "px";
            I.sldB.style.position = "absolute";
            I.sldB.style.right = j.pickerFace + "px";
            I.sldB.style.top = j.pickerFace + "px";
            I.sldB.style.border = j.pickerInset + "px solid";
            I.sldB.style.borderColor = j.pickerInsetColor;
            I.sldM.style.position = "absolute";
            I.sldM.style.right = "0";
            I.sldM.style.top = "0";
            I.sldM.style.width = jscolor.images.sld[0] + jscolor.images.arrow[0] + j.pickerFace + 2 * j.pickerInset + "px";
            I.sldM.style.height = I.box.style.height;
            try {
                I.sldM.style.cursor = "pointer"
            } catch(F) {
                I.sldM.style.cursor = "hand"
            }
            switch (b) {
            case 0:
                var H = colorPrePath+"hs.png";
                break;
            case 1:
                var H = colorPrePath+"hv.png";
                break
            }
            I.padM.style.background = "url(" + colorPrePath + "cross.gif) no-repeat";
            I.sldM.style.background = "url(" + colorPrePath + "arrow.gif) no-repeat";
            I.pad.style.background = "url('" + jscolor.getDir() + H + "') 0 0 no-repeat";
            q();
            z();
            jscolor.picker.owner = j;
            document.getElementsByTagName("body")[0].appendChild(I.boxB)
        }
        function q() {
            switch (b) {
            case 0:
                var F = 1;
                break;
            case 1:
                var F = 2;
                break
            }
            var J = Math.round((j.hsv[0] / 6) * (jscolor.images.pad[0] - 1));
            var I = Math.round((1 - j.hsv[F]) * (jscolor.images.pad[1] - 1));
            jscolor.picker.padM.style.backgroundPosition = (j.pickerFace + j.pickerInset + J - Math.floor(jscolor.images.cross[0] / 2)) + "px " + (j.pickerFace + j.pickerInset + I - Math.floor(jscolor.images.cross[1] / 2)) + "px";
            var p = jscolor.picker.sld.childNodes;
            switch (b) {
            case 0:
                var H = g(j.hsv[0], j.hsv[1], 1);
                for (var D = 0; D < p.length; D += 1) {
                    p[D].style.backgroundColor = "rgb(" + (H[0] * (1 - D / p.length) * 100) + "%," + (H[1] * (1 - D / p.length) * 100) + "%," + (H[2] * (1 - D / p.length) * 100) + "%)"
                }
                break;
            case 1:
                var H,
                K,
                G = [j.hsv[2], 0, 0];
                var D = Math.floor(j.hsv[0]);
                var E = D % 2 ? j.hsv[0] - D: 1 - (j.hsv[0] - D);
                switch (D) {
                case 6:
                case 0:
                    H = [0, 1, 2];
                    break;
                case 1:
                    H = [1, 0, 2];
                    break;
                case 2:
                    H = [2, 0, 1];
                    break;
                case 3:
                    H = [2, 1, 0];
                    break;
                case 4:
                    H = [1, 2, 0];
                    break;
                case 5:
                    H = [0, 2, 1];
                    break
                }
                for (var D = 0; D < p.length; D += 1) {
                    K = 1 - 1 / (p.length - 1) * D;
                    G[1] = G[0] * (1 - K * E);
                    G[2] = G[0] * (1 - K);
                    p[D].style.backgroundColor = "rgb(" + (G[H[0]] * 100) + "%," + (G[H[1]] * 100) + "%," + (G[H[2]] * 100) + "%)"
                }
                break
            }
        }
        function z() {
            switch (b) {
            case 0:
                var p = 2;
                break;
            case 1:
                var p = 1;
                break
            }
            var D = Math.round((1 - j.hsv[p]) * (jscolor.images.sld[1] - 1));
            jscolor.picker.sldM.style.backgroundPosition = "0 " + (j.pickerFace + j.pickerInset + D - Math.floor(jscolor.images.arrow[1] / 2)) + "px"
        }
        function s() {
            return jscolor.picker && jscolor.picker.owner === j
        }
        function o() {
            if (a === A) {
                j.importColor()
            }
            if (j.pickerOnfocus) {
                j.hidePicker()
            }
        }
        function k() {
            if (a !== A) {
                j.importColor()
            }
        }
        function u(D) {
            var F = jscolor.getMousePos(D);
            var p = F[0] - n[0];
            var E = F[1] - n[1];
            switch (b) {
            case 0:
                j.fromHSV(p * (6 / (jscolor.images.pad[0] - 1)), 1 - E / (jscolor.images.pad[1] - 1), null, e);
                break;
            case 1:
                j.fromHSV(p * (6 / (jscolor.images.pad[0] - 1)), null, 1 - E / (jscolor.images.pad[1] - 1), e);
                break
            }
        }
        function h(p) {
            var E = jscolor.getMousePos(p);
            var D = E[1] - n[1];
            switch (b) {
            case 0:
                j.fromHSV(null, null, 1 - D / (jscolor.images.sld[1] - 1), t);
                break;
            case 1:
                j.fromHSV(null, 1 - D / (jscolor.images.sld[1] - 1), null, t);
                break
            }
        }
        var j = this;
        var b = this.pickerMode.toLowerCase() === "hvs" ? 1: 0;
        var l = false;
        var a = jscolor.fetchElement(this.valueElement),
        C = jscolor.fetchElement(this.styleElement);
        var c = false,
        m = false;
        var n,
        x;
        var v = 1 << 0,
        B = 1 << 1,
        t = 1 << 2,
        e = 1 << 3;
        jscolor.addEvent(A, "focus", 
        function() {
            if (j.pickerOnfocus) {
                j.showPicker()
            }
        });
        jscolor.addEvent(A, "blur", 
        function() {
            if (!l) {
                window.setTimeout(function() {
                    l || o();
                    l = false
                },
                0)
            } else {
                l = false
            }
        });
        if (a) {
            var y = function() {
                j.fromString(a.value, v)
            };
            jscolor.addEvent(a, "keyup", y);
            jscolor.addEvent(a, "input", y);
            jscolor.addEvent(a, "blur", k);
            a.setAttribute("autocomplete", "off")
        }
        if (C) {
            C.jscStyle = {
                backgroundColor: C.style.backgroundColor,
                color: C.style.color
            }
        }
        switch (b) {
        case 0:
            jscolor.requireImage("hs.png");
            break;
        case 1:
            jscolor.requireImage("hv.png");
            break
        }
        jscolor.requireImage("cross.gif");
        jscolor.requireImage("arrow.gif");
        this.importColor()
    }
};
jscolor.install();