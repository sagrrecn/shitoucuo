var jasmine = (function (P) {
    "use strict";

    const le = "";
    const ue = "";

    /**
     * Sticky Sidebar JavaScript Plugin.
     * @version 3.3.2
     * @author SHITOUCUO
     * @license The MIT License (MIT)
     */
    const G = (() => {
        const c = ".stickySidebar";
        const m = {
            topSpacing: 0,
            bottomSpacing: 0,
            containerSelector: !1,
            innerWrapperSelector: ".inner-wrapper-sticky",
            stickyClass: "is-affixed",
            resizeSensor: !0,
            minWidth: !1
        };

        class l {
            constructor(e, r = {}) {
                if (this.options = l.extend(m, r), this.sidebar = typeof e == "string" ? document.querySelector(e) : e, typeof this.sidebar > "u") throw new Error("There is no specific sidebar element.");
                this.sidebarInner = !1, this.container = this.sidebar.parentElement, this.affixedType = "STATIC", this.direction = "down", this.support = {
                    transform: !1,
                    transform3d: !1
                }, this._initialized = !1, this._reStyle = !1, this._breakpoint = !1, this._resizeListeners = [], this.dimensions = {
                    translateY: 0,
                    topSpacing: 0,
                    lastTopSpacing: 0,
                    bottomSpacing: 0,
                    lastBottomSpacing: 0,
                    sidebarHeight: 0,
                    sidebarWidth: 0,
                    containerTop: 0,
                    containerHeight: 0,
                    viewportHeight: 0,
                    viewportTop: 0,
                    lastViewportTop: 0
                }, ["handleEvent"].forEach(n => {
                    this[n] = this[n].bind(this)
                }), this.initialize()
            }

            initialize() {
                if (this._setSupportFeatures(), this.options.innerWrapperSelector && (this.sidebarInner = this.sidebar.querySelector(this.options.innerWrapperSelector), this.sidebarInner === null && (this.sidebarInner = !1)), !this.sidebarInner) {
                    let e = document.createElement("div");
                    for (e.setAttribute("class", "inner-wrapper-sticky"), this.sidebar.appendChild(e); this.sidebar.firstChild != e;) e.appendChild(this.sidebar.firstChild);
                    this.sidebarInner = this.sidebar.querySelector(".inner-wrapper-sticky")
                }
                if (this.options.containerSelector) {
                    let e = document.querySelectorAll(this.options.containerSelector);
                    if (e = Array.prototype.slice.call(e), e.forEach((r, n) => {
                        r.contains(this.sidebar) && (this.container = r)
                    }), !e.length) throw new Error("The container does not contains on the sidebar.")
                }
                typeof this.options.topSpacing != "function" && (this.options.topSpacing = parseInt(this.options.topSpacing) || 0), typeof this.options.bottomSpacing != "function" && (this.options.bottomSpacing = parseInt(this.options.bottomSpacing) || 0), this._widthBreakpoint(), this.calcDimensions(), this.stickyPosition(), this.bindEvents(), this._initialized = !0
            }

            bindEvents() {
                window.addEventListener("resize", this, {
                    passive: !0,
                    capture: !1
                }), window.addEventListener("scroll", this, {
                    passive: !0,
                    capture: !1
                }), this.sidebar.addEventListener("update" + c, this)
            }

            handleEvent(e) {
                this.updateSticky(e)
            }

            calcDimensions() {
                if (!this._breakpoint) {
                    var e = this.dimensions;
                    e.containerTop = l.offsetRelative(this.container).top, e.containerHeight = this.container.clientHeight, e.containerBottom = e.containerTop + e.containerHeight, e.sidebarHeight = this.sidebarInner.offsetHeight, e.sidebarWidth = this.sidebar.offsetWidth, e.viewportHeight = window.innerHeight, this._calcDimensionsWithScroll()
                }
            }

            _calcDimensionsWithScroll() {
                var e = this.dimensions;
                e.sidebarLeft = l.offsetRelative(this.sidebar).left, e.viewportTop = document.documentElement.scrollTop || document.body.scrollTop, e.viewportBottom = e.viewportTop + e.viewportHeight, e.viewportLeft = document.documentElement.scrollLeft || document.body.scrollLeft, e.topSpacing = this.options.topSpacing, e.bottomSpacing = this.options.bottomSpacing, typeof e.topSpacing == "function" && (e.topSpacing = parseInt(e.topSpacing(this.sidebar)) || 0), typeof e.bottomSpacing == "function" && (e.bottomSpacing = parseInt(e.bottomSpacing(this.sidebar)) || 0), this.affixedType === "VIEWPORT-TOP" ? e.topSpacing < e.lastTopSpacing && (e.translateY += e.lastTopSpacing - e.topSpacing, this._reStyle = !0) : this.affixedType === "VIEWPORT-BOTTOM" && e.bottomSpacing < e.lastBottomSpacing && (e.translateY += e.lastBottomSpacing - e.bottomSpacing, this._reStyle = !0), e.lastTopSpacing = e.topSpacing, e.lastBottomSpacing = e.bottomSpacing
            }

            isSidebarFitsViewport() {
                return this.dimensions.sidebarHeight < this.dimensions.viewportHeight
            }

            observeScrollDir() {
                var e = this.dimensions;
                if (e.lastViewportTop !== e.viewportTop) {
                    var r = this.direction === "down" ? Math.min : Math.max;
                    e.viewportTop === r(e.viewportTop, e.lastViewportTop) && (this.direction = this.direction === "down" ? "up" : "down")
                }
            }

            getAffixType() {
                var e = this.dimensions,
                    r = !1;
                this._calcDimensionsWithScroll();
                var n = e.sidebarHeight + e.containerTop,
                    t = e.viewportTop + e.topSpacing,
                    s = e.viewportBottom - e.bottomSpacing;
                return this.direction === "up" ? t <= e.containerTop ? (e.translateY = 0, r = "STATIC") : t <= e.translateY + e.containerTop ? (e.translateY = t - e.containerTop, r = "VIEWPORT-TOP") : !this.isSidebarFitsViewport() && e.containerTop <= t && (r = "VIEWPORT-UNBOTTOM") : this.isSidebarFitsViewport() ? e.sidebarHeight + t >= e.containerBottom ? (e.translateY = e.containerBottom - n, r = "CONTAINER-BOTTOM") : t >= e.containerTop && (e.translateY = t - e.containerTop, r = "VIEWPORT-TOP") : e.containerBottom <= s ? (e.translateY = e.containerBottom - n, r = "CONTAINER-BOTTOM") : n + e.translateY <= s ? (e.translateY = s - n, r = "VIEWPORT-BOTTOM") : e.containerTop + e.translateY <= t && (r = "VIEWPORT-UNBOTTOM"), e.translateY = Math.max(0, e.translateY), e.translateY = Math.min(e.containerHeight, e.translateY), e.lastViewportTop = e.viewportTop, r
            }

            _getStyle(e) {
                if (!(typeof e > "u")) {
                    var r = {
                            inner: {},
                            outer: {}
                        },
                        n = this.dimensions;
                    switch (e) {
                        case "VIEWPORT-TOP":
                            r.inner = {
                                position: "fixed",
                                top: n.topSpacing,
                                left: n.sidebarLeft - n.viewportLeft,
                                width: n.sidebarWidth
                            };
                            break;
                        case "VIEWPORT-BOTTOM":
                            r.inner = {
                                position: "fixed",
                                top: "auto",
                                left: n.sidebarLeft,
                                bottom: n.bottomSpacing,
                                width: n.sidebarWidth
                            };
                            break;
                        case "CONTAINER-BOTTOM":
                        case "VIEWPORT-UNBOTTOM":
                            let t = this._getTranslate(0, n.translateY + "px");
                            t ? r.inner = {
                                transform: t
                            } : r.inner = {
                                position: "absolute",
                                top: n.translateY,
                                width: n.sidebarWidth
                            };
                            break
                    }
                    switch (e) {
                        case "VIEWPORT-TOP":
                        case "VIEWPORT-BOTTOM":
                        case "VIEWPORT-UNBOTTOM":
                        case "CONTAINER-BOTTOM":
                            r.outer = {
                                height: n.sidebarHeight,
                                position: "relative"
                            };
                            break
                    }
                    return r.outer = l.extend({
                        height: "",
                        position: ""
                    }, r.outer), r.inner = l.extend({
                        position: "relative",
                        top: "",
                        left: "",
                        bottom: "",
                        width: "",
                        transform: this._getTranslate()
                    }, r.inner), r
                }
            }

            stickyPosition(e) {
                if (!this._breakpoint) {
                    e = this._reStyle || e || !1, this.options.topSpacing, this.options.bottomSpacing;
                    var r = this.getAffixType(),
                        n = this._getStyle(r);
                    if ((this.affixedType != r || e) && r) {
                        let t = "affix." + r.toLowerCase().replace("viewport-", "") + c;
                        l.eventTrigger(this.sidebar, t), r === "STATIC" ? l.removeClass(this.sidebar, this.options.stickyClass) : l.addClass(this.sidebar, this.options.stickyClass);
                        for (let d in n.outer) n.outer[d], this.sidebar.style[d] = n.outer[d];
                        for (let d in n.inner) {
                            let g = typeof n.inner[d] == "number" ? "px" : "";
                            this.sidebarInner.style[d] = n.inner[d] + g
                        }
                        let s = "affixed." + r.toLowerCase().replace("viewport-", "") + c;
                        l.eventTrigger(this.sidebar, s)
                    } else this._initialized && (this.sidebarInner.style.left = n.inner.left);
                    this.affixedType = r
                }
            }

            _widthBreakpoint() {
                window.innerWidth <= this.options.minWidth ? (this._breakpoint = !0, this.affixedType = "STATIC", this.sidebar.removeAttribute("style"), l.removeClass(this.sidebar, this.options.stickyClass), this.sidebarInner.removeAttribute("style")) : this._breakpoint = !1
            }

            updateSticky(e = {}) {
                this._running || (this._running = !0, (r => {
                    requestAnimationFrame(() => {
                        switch (r) {
                            case "scroll":
                                this._calcDimensionsWithScroll(), this.observeScrollDir(), this.stickyPosition();
                                break;
                            case "resize":
                            default:
                                this._widthBreakpoint(), this.calcDimensions(), this.stickyPosition(!0);
                                break
                        }
                        this._running = !1
                    })
                })(e.type))
            }

            _setSupportFeatures() {
                var e = this.support;
                e.transform = l.supportTransform(), e.transform3d = l.supportTransform(!0)
            }

            _getTranslate(e = 0, r = 0, n = 0) {
                return this.support.transform3d ? "translate3d(" + e + ", " + r + ", " + n + ")" : this.support.translate ? "translate(" + e + ", " + r + ")" : !1
            }

            destroy() {
                window.removeEventListener("resize", this, {
                    caption: !1
                }), window.removeEventListener("scroll", this, {
                    caption: !1
                }), this.sidebar.classList.remove(this.options.stickyClass), this.sidebar.style.minHeight = "", this.sidebar.removeEventListener("update" + c, this);
                var e = {
                    inner: {},
                    outer: {}
                };
                e.inner = {
                    position: "",
                    top: "",
                    left: "",
                    bottom: "",
                    width: "",
                    transform: ""
                }, e.outer = {
                    height: "",
                    position: ""
                };
                for (let r in e.outer) this.sidebar.style[r] = e.outer[r];
                for (let r in e.inner) this.sidebarInner.style[r] = e.inner[r];
            }

            static supportTransform(e) {
                var r = !1,
                    n = e ? "perspective" : "transform",
                    t = n.charAt(0).toUpperCase() + n.slice(1),
                    s = ["Webkit", "Moz", "O", "ms"],
                    d = document.createElement("support"),
                    g = d.style;
                return (n + " " + s.join(t + " ") + t).split(" ").forEach(function (E, y) {
                    if (g[E] !== void 0) return r = E, !1
                }), r
            }

            static eventTrigger(e, r, n) {
                try {
                    var t = new CustomEvent(r, {
                        detail: n
                    })
                } catch {
                    var t = document.createEvent("CustomEvent");
                    t.initCustomEvent(r, !0, !0, n)
                }
                e.dispatchEvent(t)
            }

            static extend(e, r) {
                var n = {};
                for (let t in e) typeof r[t] < "u" ? n[t] = r[t] : n[t] = e[t];
                return n
            }

            static offsetRelative(e) {
                var r = {
                    left: 0,
                    top: 0
                };
                do {
                    let n = e.offsetTop,
                        t = e.offsetLeft;
                    isNaN(n) || (r.top += n), isNaN(t) || (r.left += t), e = e.tagName === "BODY" ? e.parentElement : e.offsetParent
                } while (e);
                return r
            }

            static addClass(e, r) {
                l.hasClass(e, r) || (e.classList ? e.classList.add(r) : e.className += " " + r)
            }

            static removeClass(e, r) {
                l.hasClass(e, r) && (e.classList ? e.classList.remove(r) : e.className = e.className.replace(new RegExp("(^|\\b)" + r.split(" ").join("|") + "(\\b|$)", "gi"), " "))
            }

            static hasClass(e, r) {
                return e.classList ? e.classList.contains(r) : new RegExp("(^| )" + r + "( |$)", "gi").test(e.className)
            }
        }
        return l
    })();

    window.StickySidebar = G;

    /**
     * 主题管理功能 - 修改为默认深色模式
     */
    function switchDark() {
        // 切换主题并保存到localStorage
        if (!localStorage.theme) {
            // 首次设置，设为深色模式
            localStorage.theme = "dark";
        } else if (localStorage.theme === "light") {
            localStorage.theme = "dark";
        } else if (localStorage.theme === "dark") {
            localStorage.theme = "light";
        }
        loadTheme();
        updateThemeIcon();
    }

    function loadTheme() {
        // 移除系统主题变化监听器，不再自动跟随系统主题
        
        // 应用当前主题
        if (!localStorage.theme) {
            // 首次访问：默认设为深色模式
            localStorage.theme = "dark";
            document.documentElement.classList.add("dark");
        } else if (localStorage.theme === "dark") {
            document.documentElement.classList.add("dark");
        } else {
            document.documentElement.classList.remove("dark");
        }

        updateThemeIcon();
    }

    // 获取当前主题状态
    function getCurrentTheme() {
        if (!localStorage.theme) {
            // 首次访问：默认返回深色模式
            return "dark";
        }
        return localStorage.theme;
    }

    // 更新主题图标
    function updateThemeIcon() {
        const currentTheme = getCurrentTheme();
        const iconElement = document.querySelector('[title="日夜模式"] iconify-icon');

        if (iconElement) {
            // 根据当前主题更新图标
            if (currentTheme === "dark") {
                iconElement.setAttribute('icon', 'tabler:sun'); // 深色模式显示太阳图标（切换后变为浅色）
            } else {
                iconElement.setAttribute('icon', 'tabler:moon'); // 浅色模式显示月亮图标（切换后变为深色）
            }
        }
    }

    /**
     * 其他工具函数
     */
    function backtop() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    }

    function clickSearch() {
        const searchInput = document.getElementById("search-input");
        if (searchInput) {
            searchInput.blur();
        }
    }

    function toggleMobileMenu() {
        const mobileMenusBg = document.querySelector("#mobile-menus-bg");
        const mobileMenus = document.querySelector("#mobile-menus");

        if (mobileMenus.classList.contains("!translate-x-0")) {
            if (mobileMenusBg) mobileMenusBg.classList.add("hidden");
            if (mobileMenus) mobileMenus.classList.remove("!translate-x-0");
        } else {
            if (mobileMenusBg) mobileMenusBg.classList.remove("hidden");
            if (mobileMenus) mobileMenus.classList.add("!translate-x-0");
        }
    }

    /**
     * 初始化函数
     */
    function initializeJasmine() {
        // 初始化主题 - 默认深色模式
        loadTheme();

        // 初始化粘性侧边栏
        if (document.querySelector("#sidebar-right")) {
            new G("#sidebar-right", {
                innerWrapperSelector: ".sidebar__right__inner"
            });
        }

        // 初始化导航项悬停效果
        Array.from(document.getElementsByClassName("nav-li")).forEach(item => {
            item.addEventListener("mouseover", () => {
                const span = item.getElementsByTagName("span")[0];
                if (span) span.classList.add("!block");
            });
            item.addEventListener("mouseout", () => {
                const span = item.getElementsByTagName("span")[0];
                if (span) span.classList.remove("!block");
            });
        });

        // 初始化移动菜单背景点击事件
        const mobileMenusBg = document.querySelector("#mobile-menus-bg");
        if (mobileMenusBg) {
            mobileMenusBg.addEventListener("click", toggleMobileMenu);
        }

        // 初始化主题图标
        updateThemeIcon();

        console.log("%c Jasmine ", "background:#000;color:#fff", "https://www.shitoucuo.com");
    }

    // 页面加载完成后初始化
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeJasmine);
    } else {
        initializeJasmine();
    }

    // 导出函数到全局对象
    return {
        backtop: backtop,
        clickSearch: clickSearch,
        loadTheme: loadTheme,
        switchDark: switchDark,
        toggleMobileMenu: toggleMobileMenu,
        getCurrentTheme: getCurrentTheme,
        updateThemeIcon: updateThemeIcon
    };
})({});