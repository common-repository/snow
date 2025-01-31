/*
 * Snow for WordPress
 * http://wordpress.org/plugins/snow/
 */

! function(t) {
    t.snowfall = function(e, i) {
        function s(s, n, a, o, l) {
            this.id = l, this.x = s, this.y = n, this.size = a, this.speed = o, this.step = 0, this.stepSize = h(1, 10) / 100, i.collection && (this.target = m[h(0, m.length - 1)]);
            var r = null;
            i.image ? (r = t(document.createElement("img")), r[0].src = i.image) : (r = t(document.createElement("div")), r.css({
                background: i.flakeColor
            })), r.attr({
                "class": "snowfall-flakes",
                id: "flake-" + this.id
            }).css({
                width: this.size,
                height: this.size,
                position: i.flakePosition,
                top: this.y,
                left: this.x,
                fontSize: 0,
                zIndex: i.flakeIndex
            }), t(e).get(0).tagName === t(document).get(0).tagName ? (t("body").append(r), e = t("body")) : t(e).append(r), this.element = document.getElementById("flake-" + this.id), this.update = function() {
                if (this.y += this.speed, this.y > d - (this.size + 6) && this.reset(), this.element.style.top = this.y + "px", this.element.style.left = this.x + "px", this.step += this.stepSize, this.x += S === !1 ? Math.cos(this.step) : S + Math.cos(this.step), i.collection && this.x > this.target.x && this.x < this.target.width + this.target.x && this.y > this.target.y && this.y < this.target.height + this.target.y) {
                    var t = this.target.element.getContext("2d"),
                        e = this.x - this.target.x,
                        s = this.y - this.target.y,
                        n = this.target.colData;
                    if (void 0 !== n[parseInt(e)][parseInt(s + this.speed + this.size)] || s + this.speed + this.size > this.target.height)
                        if (s + this.speed + this.size > this.target.height) {
                            for (; s + this.speed + this.size > this.target.height && this.speed > 0;) this.speed *= .5;
                            t.fillStyle = "#fff", void 0 == n[parseInt(e)][parseInt(s + this.speed + this.size)] ? (n[parseInt(e)][parseInt(s + this.speed + this.size)] = 1, t.fillRect(e, s + this.speed + this.size, this.size, this.size)) : (n[parseInt(e)][parseInt(s + this.speed)] = 1, t.fillRect(e, s + this.speed, this.size, this.size)), this.reset()
                        } else this.speed = 1, this.stepSize = 0, parseInt(e) + 1 < this.target.width && void 0 == n[parseInt(e) + 1][parseInt(s) + 1] ? this.x++ : parseInt(e) - 1 > 0 && void 0 == n[parseInt(e) - 1][parseInt(s) + 1] ? this.x-- : (t.fillStyle = "#fff", t.fillRect(e, s, this.size, this.size), n[parseInt(e)][parseInt(s)] = 1, this.reset())
                }(this.x > p - f || this.x < f) && this.reset()
            }, this.reset = function() {
                this.y = 0, this.x = h(f, p - f), this.stepSize = h(1, 10) / 100, this.size = h(100 * i.minSize, 100 * i.maxSize) / 100, this.speed = h(i.minSpeed, i.maxSpeed)
            }
        }

        function n() {
            for (r = 0; r < o.length; r += 1) o[r].update();
            c = requestAnimationFrame(function() {
                n()
            })
        }
        var a = {
                flakeCount: 35,
                flakeColor: "#ffffff",
                flakePosition: "absolute",
                flakeIndex: 999999,
                minSize: 1,
                maxSize: 2,
                minSpeed: 1,
                maxSpeed: 5,
                round: !1,
                shadow: !1,
                collection: !1,
                collectionHeight: 40,
                deviceorientation: !1
            },
            i = t.extend(a, i),
            h = function(t, e) {
                return Math.round(t + Math.random() * (e - t))
            };
        t(e).data("snowfall", this);
        var o = [],
            l = 0,
            r = 0,
            d = t(e).height(),
            p = t(e).width(),
            f = 0,
            c = 0;
        if (i.collection !== !1) {
            var g = document.createElement("canvas");
            if (g.getContext && g.getContext("2d"))
                for (var m = [], x = t(i.collection), u = i.collectionHeight, r = 0; r < x.length; r++) {
                    var z = x[r].getBoundingClientRect(),
                        w = t("<canvas/>", {
                            "class": "snowfall-canvas"
                        }),
                        v = [];
                    if (z.top - u > 0) {
                        t("body").append(w), w.css({
                            position: i.flakePosition,
                            left: z.left + "px",
                            top: z.top - u + "px"
                        }).prop({
                            width: z.width,
                            height: u
                        });
                        for (var y = 0; y < z.width; y++) v[y] = [];
                        m.push({
                            element: w.get(0),
                            x: z.left,
                            y: z.top - u,
                            width: z.width,
                            height: u,
                            colData: v
                        })
                    }
                } else i.collection = !1
        }
        for (t(e).get(0).tagName === t(document).get(0).tagName && (f = 25), t(window).bind("resize", function() {
                d = t(e)[0].clientHeight, p = t(e)[0].offsetWidth
            }), r = 0; r < i.flakeCount; r += 1) l = o.length, o.push(new s(h(f, p - f), h(0, d), h(100 * i.minSize, 100 * i.maxSize) / 100, h(i.minSpeed, i.maxSpeed), l));
        i.round && t(".snowfall-flakes").css({
            "-moz-border-radius": i.maxSize,
            "-webkit-border-radius": i.maxSize,
            "border-radius": i.maxSize
        }), i.shadow && t(".snowfall-flakes").css({
            "-moz-box-shadow": "1px 1px 1px #555",
            "-webkit-box-shadow": "1px 1px 1px #555",
            "box-shadow": "1px 1px 1px #555"
        });
        var S = !1;
        i.deviceorientation && t(window).bind("deviceorientation", function(t) {
            S = .1 * t.originalEvent.gamma
        }), n(), this.clear = function() {
            t(e).children(".snowfall-flakes").remove(), t(".snowfall-canvas").remove(), o = [], cancelAnimationFrame(c)
        }
    }, t.fn.snowfall = function(e) {
        return "object" == typeof e || void 0 == e ? this.each(function() {
            new t.snowfall(this, e)
        }) : "string" == typeof e ? this.each(function() {
            var e = t(this).data("snowfall");
            e && e.clear()
        }) : void 0
    }
}(jQuery);