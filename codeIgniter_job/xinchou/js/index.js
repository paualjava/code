$(function() {
    function nt() {
        r = a.scrollTop(),
        r > t[i + 1] && (i++, rt(i)),
        r < t[i - 1] && (i--, rt(i)),
        r == 0 && (i = 0, rt(i)),
        r == l && (i = e.length - 1, rt(i)),
        r > t[2] && !X,
        r >= 0 && r < 1300 && lt(0),
        r >= 1300 && r < 2700 && (st(), lt(1)),
        r >= 2700 && lt(2)
    }
    function rt(e, t) {
        a.off("scroll", nt),
        g.stop().animate({
            top: n[e]
        },
        "normal", 
        function() {
            setTimeout(function() {
                a.on("scroll", nt)
            },
            t)
        })
    }
    function it(e, t, n) {
        e.css({
            left: t + n * S,
            top: k * N + O
        })
    }
    function st() {
        U || R.each(function(e) {
            $(this).delay(Math.random() * 2e3).animate({
                left: z[e],
                top: W[e]
            },
            1e3)
        }),
        U = !0
    }
    function ut() {
        d < v + 1 && (p.fadeIn("normal", at), d++)
    }
    function at() {
        d < v && (p.fadeOut("normal", ut), d++)
    }
    function ft() {
        $(".diamond-big").jqFloat({
            width: 10,
            height: 50,
            speed: 1e3
        }),
        $(".diamond-small").jqFloat({
            width: 10,
            height: 10,
            speed: 500
        }),
        $(".thunder").jqFloat({
            width: 10,
            height: 10,
            speed: 500
        }),
        $(".satellite").jqFloat({
            width: 20,
            height: 20,
            speed: 500
        }),
        $(".down-here").jqFloat({
            width: 0,
            height: 10,
            speed: 500
        })
    }
    function lt(e) {
        for (var t = 0; t < 3; t++) t !== e && tt[t].jqFloat("stop");
        tt[e].jqFloat("play")
    }
    var e = ["#index", "#about", "#case", "#news", "#contact"],
    t = [],
    n = [11, 57, 103, 149, 195, 241],
    r = 0,
    i = 0,
    s = 0,
    o = $(document),
    u = o.height(),
    a = $(window),
    f = a.height(),
    l = u - f,
    c = $(".introduction button"),
    h = $(".look-down"),
    p = h.eq(0),
    d = 0,
    v = 6,
    m = $("#nav"),
    g = $("#navDote"),
    y = $("#index"),
    b = $("#partnerList"),
    w = null,
    E = b.find("li").eq(0),
    S = E.width(),
    x = E.height(),
    T = Math.floor(S / 2),
    N = Math.floor(x / 2),
    C = 3,
    k = 1,
    L = 6,
    A = 3,
    O = N,
    M = $("#tag-case li"),
    _ = $(".ajax-case blockquote"),
    D = $(".case1 li"),
    P = $(".case2 li"),
    H = $(".case3 ol li"),
    B = $(".case3 ul li"),
    j = $(".case4 li"),
    F = $(".title-cite a"),
    I = $("#tag-news"),
    q = I.eq(1).find("li").eq(1).prop("id"),
    R = $(".service-box span"),
    U = !1,
    z = [],
    W = [],
    X = !1,
    V = $("#ajax-news"),
    J = $(".cloud01"),
    K = $(".cloud02"),
    Q = $(".cloud03"),
    G = [J.position().left, K.position().left, Q.position().left],
    Y = $(".screen0"),
    Z = $(".screen1"),
    et = $(".screen2"),
    tt = [Y, Z, et];
    for (; s < e.length; s++) t.push($(e[s]).offset().top);
    h.each(function(t) {
        $(this).click(function(n) {
            $.scrollTo(e[t + 1], 500),
            rt(t + 1, 500)
        })
    }),
    a.on("scroll", nt),
    m.click(function(t) {
        if (t.target.nodeName.toLowerCase() == "li") {
            var n = $(t.target).index(),
            r = 500 * Math.abs(n - i);
            $.scrollTo(e[n], r),
            i = n,
            rt(n, r),
            n == 3 && !X;
            switch (n) {
            case 0:
            case 1:
                lt(0);
                break;
            case 2:
            case 3:
                lt(1);
                break;
            case 4:
            case 5:
                lt(2);
                break;
            default:

            }
        }
    }),
    m.mouseover(function(e) {
        if (e.target.nodeName.toLowerCase() == "li") {
            var t = $(e.target).index();
            rt(t, 500)
        }
    }),
    m.mouseout(function(e) {
        rt(i, 500)
    }),
    b.html("<li></li><li></li><li></li><li>" + E.html() + "</li>" + b.html().replace(E.html(), "")),
    w = b.find("li"),
    w.eq(0).hide(),
    w.eq(1).css({
        left: S,
        top: N
    }),
    w.eq(2).css({
        left: S * 2,
        top: N
    }),
    w.each(function(e) {
        e > 2 && (e == L && (C = C == 3 ? 4: 3, A = L, L += C, k++), C == 3 ? it($(this), T, e - A) : it($(this), 0, e - A))
    }),
    R.each(function(e) {
        var t = $(this).position();
        z.push(t.left),
        W.push(t.top),
        $(this).css({
            top: t.top + Math.random() * 200 + "px"
        }),
        $(this).hasClass("r") ? $(this).css({
            left: t.left + Math.random() * 500 + 2e3 + "px"
        }) : $(this).css({
            left: t.left + Math.random() * 500 - 2e3 + "px"
        })
    }),
    M.each(function(e) {
        $(this).click(function() {
            $(this).addClass("active").siblings("li").removeClass("active"),
            _.eq(e).show().siblings("blockquote").hide()
        })
    }),
    D.hover(function() {
        $(this).stop().find("p").fadeIn(500)
    },
    function() {
        $(this).stop().find("p").fadeOut(500)
    }),
    P.hover(function() {
        $(this).addClass("active"),
        $(this).stop().find("span").fadeIn(500)
    },
    function() {
        $(this).removeClass("active"),
        $(this).stop().find("span").hide()
    }),
    B.eq(0).css({
        left: 100
    }),
    H.each(function(e) {
        $(this).click(function() {
            $(this).addClass("active").siblings("li").removeClass("active"),
            B.eq(e).animate({
                left: 100
            },
            500).siblings("li").animate({
                left: 2e3
            },
            500)
        })
    }),
    j.hover(function() {
        $(this).animate({
            height: 340
        },
        "normal")
    },
    function() {
        $(this).animate({
            height: 250
        },
        "normal")
    }),
    I.click(function(e) {
        var t = $(this).prop("id"),
        n = "",
        r = "",
        i = null;
    }),
    y.mousemove(function(e) {
        var t = ($(this).width() / 2 - e.pageX) * 100 / $(this).width();
        J.css("left", G[0] + t),
        K.css("left", G[1] + t),
        Q.css("left", G[2] + t)
    }),
    ft(),
    c.click(function() {
        $.scrollTo(300, 500),
        d = 0,
        ut()
    }),
    lt(0)
})