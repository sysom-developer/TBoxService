function setAddValue(e) {
	function a(e, a) {
		if (e && a) {
			var n = e.innerHTML;
			"" === n && (n = 1);
			var t = 1 * n,
				r = keepNum(a * t);
			e.innerHTML = r
		}
	}
	for (var n = _$("J_insureType"), t = e.insureTypes, r = e.extInfo, i = 0, s = t.length; s > i; i++) {
		if ("1142" === t[i].code)
			for (var o in t[i]) $(".J_" + o + "_" + t[i].code).each(function() {
				var e = this.innerHTML;
				if (e) {
					var a = 1 * e,
						n = keepNum(t[i][o] * a);
					$(this).html(n)
				} else this.innerHTML = t[i][o]
			});
		if ($(".J_" + t[i].code).css("display", "block"), $(".J_" + t[i].code).parent().parent().css("display", "block"), $(".J_duration_" + t[i].code).html(t[i].duration), $(".J_link_" + t[i].code).attr("href", t[i].src), $(".J_shouyiren_" + t[i].code).html(t[i].shouyiren), $(".J_baoe_" + t[i].code).each(function() {
				a(this, t[i].baoe)
			}), ("521" == t[i].code || "522" == t[i].code) && ($(".J_ylBaoxiao").css("display", "block"), $(".J_ylBaoxiao").parent().parent().css("display", "block"), t[i].isCheck && $(".J_isCheck").css("display", "block"), $(".J_baoe_ylBaoxiao").each(function() {
				a(this, t[i].baoe)
			}), t[i].baoe += "份"), "74401" == t[i].code && $(".J_sumAge_" + t[i].code).html(t[i].sumAge + "岁前"), ("507" == t[i].code || "508" == t[i].code) && ($(".J_ylBaoxiao1").css("display", "block"), $(".J_ylBaoxiao1").parent().parent().css("display", "block"), t[i].isCheck && $(".J_isCheck_1").css("display", "block"), $(".J_baoe_ylBaoxiao1").each(function() {
				a(this, t[i].baoe), $(".J_link_ylBaoxiao1").attr("href", t[i].src)
			}), t[i].baoe += "份"), ("527" == t[i].code || "528" == t[i].code || "529" == t[i].code || "530" == t[i].code) && ($(".J_ylYiwai").css("display", "block"), $(".J_ylYiwai").parent().parent().css("display", "block"), $(".J_baoe_ylYiwai").each(function() {
				a(this, t[i].baoe), $(".J_link_ylYiwai").attr("href", t[i].src)
			})), ("557" == t[i].code || "558" == t[i].code) && ($(".J_anXiang").css("display", "block"), $(".J_anXiang").parent().parent().css("display", "block"), $(".J_link_anXiang").attr("href", t[i].src), $(".J_" + t[i].code + "_tem").css("display", "inline-block"), t[i].planType)) {
			var c = t[i].planType;
			"1" == c || 1 == c ? $(".J_anXiang_plan").html(500) : "2" == c || 2 == c ? $(".J_anXiang_plan").html(1e3) : "3" == c || 3 == c ? $(".J_anXiang_plan").html(1500) : ("4" == c || 4 == c) && $(".J_anXiang_plan").html(2e3)
		}("559" == t[i].code || "560" == t[i].code) && ($(".J_anKang").css("display", "block"), $(".J_anKang").parent().parent().css("display", "block"), $(".J_link_anKang").attr("href", t[i].src), $(".J_" + t[i].code + "_tem").css("display", "inline-block")), "516" == t[i].code && (t[i].baoe += "份"), "m38" == t[i].code && ($(".J_noXinli").css("display", "none"), $(".J_hasXinLi").css("display", "block"));
		var l = t[i].baof;
		if (n) {
			var d = "<tr align='center'><td>{{name}}</td><td class='sf00'>{{baoe}}</td><td>{{duration}}</td><td>{{jiaofei}}</td><td class='sf00'>{{baof}}</td></tr>";
			d = d.replace("{{name}}", t[i].name).replace("{{duration}}", t[i].duration).replace("{{jiaofei}}", t[i].jiaofei).replace("{{baof}}", "string" == typeof l ? l : floatNum(l)).replace("{{baoe}}", t[i].baoe), n.innerHTML += d
		}
	}
	if (r)
		for (var h in r) "src" == h && $(".J_link_ext").attr("href", r[h]), $(".J_" + h).html(r[h])
}

function setValue(e) {
	function a(a, n, t) {
		var r = t ? e[n][t] : e[n],
			i = "" == a.innerHTML ? "1" : a.innerHTML,
			s = [],
			o = 0,
			c = 0,
			l = 0;
		if (i.indexOf(".") > -1) {
			s = i.split("."), o = s[1].length, c = parseInt(s[0]) * r, l = parseInt(s[1]) * r;
			for (var d = 0; o > d; d++) l /= 10;
			c += l
		} else c = parseInt(i) * r;
		a.innerHTML = keepNum(c)
	}
	var n = !1;
	for (var t in e) switch (t) {
		case "age":
			$(".J_age").html(e[t] + "岁");
			break;
		case "baoe":
			for (var r in e[t]) "main" != r ? $(".J_baoe_" + r).each(null == e[t][r] || "null" == e[t][r] ? function() {
				this.parentNode.parentNode.parentNode.style.display = "none"
			} : function() {
				a(this, t, r), this.parentNode.parentNode.parentNode.style.display = "block"
			}) : $(".J_baoe").each(function() {
				a(this, t, r)
			});
			break;
		case "baofei":
			for (var r in e[t]) "main" != r ? $(".J_baofei_" + r).each(function() {
				a(this, t, r)
			}) : $(".J_baofei").each(function() {
				a(this, t, r)
			});
			break;
		case "extInfo":
			for (var i in e[t]) "isShow" == i && (n = e[t][i]), "null" == e[t][i] || 0 == e[t][i] || "undefined" == typeof e[t][i] || null == e[t][i] ? "getSum" == $(".J_ext_" + i).parent().attr("class") ? $(".J_ext_" + i).html(0) : $(".J_ext_" + i).parent().hide() : $(".J_ext_" + i).html(e[t][i]), "level" == i && $(".J_ext_" + i).each(function() {
				this.innerHTML = e[t][i]
			});
			break;
		case "totalbaofei":
			$(".J_totalbaofei").each(function() {
				a(this, t)
			});
			break;
		case "cardLink":
			$(".J_cardLink").attr("href", e[t]), $(".J_cardLink_").attr("href", e[t]);
			break;
		case "salesManSex":
			$(".J_cardLink").html(1 === e.salesManSex ? "查看他的名片" : "查看她的名片");
			break;
		case "low":
			if ("undefined" == typeof dataArr) break;
			dataArr[0] = e[t];
			break;
		case "mid":
			if ("undefined" == typeof dataArr) break;
			dataArr[1] = e[t];
			break;
		case "high":
			if ("undefined" == typeof dataArr) break;
			dataArr[2] = e[t];
			break;
		case "low1":
			if ("undefined" == typeof dataArr1) break;
			dataArr1[0] = e[t];
			break;
		case "mid1":
			if ("undefined" == typeof dataArr1) break;
			dataArr1[1] = e[t];
			break;
		case "high1":
			if ("undefined" == typeof dataArr1) break;
			dataArr1[2] = e[t];
			break;
		case "low2":
			if ("undefined" == typeof dataArr2) break;
			dataArr2[0] = e[t];
			break;
		case "mid2":
			if ("undefined" == typeof dataArr2) break;
			dataArr2[1] = e[t];
			break;
		case "high2":
			if ("undefined" == typeof dataArr2) break;
			dataArr2[2] = e[t];
			break;
		case "low3":
			if ("undefined" == typeof dataArr3) break;
			dataArr3[0] = e[t];
			break;
		case "mid3":
			if ("undefined" == typeof dataArr3) break;
			dataArr3[1] = e[t];
			break;
		case "high3":
			if ("undefined" == typeof dataArr3) break;
			dataArr3[2] = e[t];
			break;
		case "starAge":
			starAge = e[t];
			break;
		case "userPhoto":
			$(".managerPhoto img").attr("src", e[t]), $(".J_visitPhoto").attr("src", e[t]);
			break;
		case "mobile":
			$(".telOut").attr("href", "tel:" + e[t]), $(".J_" + t).html(e[t]);
			break;
		case "ideaUrl":
			pageUrl[0] = e[t];
			break;
		case "aboutUrl":
			pageUrl[1] = e[t];
			break;
		case "address":
			if ("暂未填写" == e.address) {
				$(".J_address").parent().css("display", "none"), $("#card_message").css("font-size", "1.3em");
				break
			}
			$(".J_address").html(e.address.length > 30 ? e[t].substr(0, 27) + "..." : e[t]);
			break;
		default:
			$(".J_totalbaofei_keep").each(function() {
				$(this).html(floatNum(e.totalbaofei))
			}), $(".J_" + t).each(function() {
				var a = this.innerHTML;
				if (a && !isNaN(parseInt(a)) && "number" == typeof e[t]) {
					var n = 1 * a,
						r = keepNum(e[t] * n);
					$(this).html(r)
				} else this.innerHTML = e[t]
			})
	}
	n || $(".J_ext_add1Total").parent().parent().hide(),
		function() {
			$(".getSum").each(function() {
				for (var e = 0, a = 0, n = this.children.length; n > a; a++) e += concatString(this.children[a].innerHTML);
				this.innerHTML = toThousands(e)
			}), $(".getSumAge").each(function() {
				for (var e = 0, a = 0, n = this.children.length; n > a; a++) {
					var t = this.children[a].innerHTML;
					t = parseInt(t ? t : 0), t = isNaN(t) ? 0 : t, e += t
				}
				this.innerHTML = e + "岁"
			}), $(".getMinAge").each(function() {
				for (var e = 0, a = 0, n = this.children.length; n > a; a++) 0 != a ? e -= parseInt(this.children[a].innerHTML) : e = parseInt(this.children[0].innerHTML);
				this.innerHTML = e + "岁"
			}), $(".pk").each(function() {
				for (var e = this.children.length, a = parseInt(this.children[e - 1].innerHTML), n = 0, t = 0; e - 1 > t; t++) n += concatString(this.children[t].innerHTML);
				n = n >= a ? a : n, this.innerHTML = toThousands(n)
			})
		}()
}

function keepNum(e) {
	if (e += "", -1 == e.indexOf(".")) return toThousands(parseInt(e));
	var a = e.split("."),
		n = parseInt(a[0]);
	if (1 == a[1].length && "0" !== a[1]) n = toThousands(n) + "." + a[1] + "0";
	else if (2 == a[1].length && "00" !== a[1]) n = toThousands(n) + "." + a[1];
	else if (a[1].length > 2 && "000" !== a[1]) {
		var t = a[1].substr(2, 1) ? parseInt(a[1].substr(2, 1)) : 0,
			r = parseInt(a[1].substr(1, 1)),
			i = parseInt(a[1].substr(0, 1));
		t >= 5 && (r + 1 == 10 ? i + 1 == 10 ? (n++, i = 0, r = 0) : (i++, r = 0) : r++), n = 0 == i && 0 == r ? toThousands(n) : toThousands(n) + "." + i + r
	}
	return n
}

function floatNum(e) {
	if ("undefined" != typeof e) {
		var a = parseInt(e),
			n = e.toString(),
			t = n.indexOf(".") > -1 ? n.split(".")[1] : "";
		if (0 == t.length) t = "00";
		else if (1 == t.length) t += "0";
		else {
			var r = t.substr(2, 1) ? parseInt(t.substr(2, 1)) : 0,
				i = parseInt(t.substr(1, 1)),
				s = parseInt(t.substr(0, 1));
			r >= 5 && (i + 1 == 10 ? s + 1 == 10 ? (a++, s = 0, i = 0) : (s++, i = 0) : i++), t = 0 == s && 0 == i ? "00" : s + "" + i
		}
		return toThousands(a) + "." + t
	}
}

function toThousands(e) {
	var a = [],
		n = 0;
	e = (e || 0).toString().split("");
	for (var t = e.length - 1; t >= 0; t--) n++, a.unshift(e[t]), n % 3 || 0 == t || a.unshift(",");
	return a.join("")
}

function concatString(e) {
	for (var a = e.split(","), n = 0, t = 0, r = a.length; r > t; t++) n += a[t];
	return parseInt(n)
}

function setFrame(e, a, n) {
	return e ? (_$(a).style.display = "none", void(_$(n).style.marginTop = "0px")) : void Zepto(function(e) {
		var t = e(a + " li"),
			r = e(window).height();
		t.eq(0).attr("class", "active"), _$(n).children[0].style.height = _$(n).children[0].children[0].children[0].offsetHeight + "px", p_swiper = new Swiper("#" + n, {
			onTouchStart: function(e) {
				e.touches.startX < 15 && p_swiper.lockSwipes()
			},
			onTouchEnd: function(e) {
				p_swiper.unlockSwipes()
			},
			onSlideChangeStart: function(a) {
				var i = a.activeIndex;
				if (1 == i) {
					var s = _$(n).offsetTop;
					e(".swiper-wrapper").height(r - s)
				}
				t.removeClass().eq(i).addClass("active"), loadPage(n, i)
			},
			onSlideChangeEnd: function(a) {
				var n = e(".swiper-slide.swiper-slide-active .content-slide").height();
				e(".swiper-wrapper").height(n), e(window).scrollTop(0)
			}
		}), t.on("click", function(a) {
			a.preventDefault();
			var n = e(this);
			n.addClass("active").siblings().removeClass(), p_swiper.slideTo(n.index())
		})
	})
}

function shineLogo(e) {
	var a = _$(e);
	if (a) {
		var n = a.getElementsByTagName("img")[0].src;
		return a.style.cssText = "display:block;background:none;width:auto;position:absolute;right:0;top:10%;z-index:11;border-radius:50px 0 0 50px;background:rgba(176,196,213,0.6);line-height:30px;padding:0 20px;color:#fff;font-size:14px;box-shadow:1px 1px 0px 1px #cacaca", void(n.search(/renshou/) > 0 ? a.innerHTML = "中国人寿" : n.search(/pingan/) > 0 ? a.innerHTML = "中国平安" : n.search(/yingda/) > 0 ? a.innerHTML = "英大人寿" : n.search(/bainian/) > 0 ? a.innerHTML = "百年人寿" : n.search(/guangda/) > 0 ? a.innerHTML = "光大永明" : n.search(/guohua/) > 0 ? a.innerHTML = "国华人寿" : n.search(/hezong/) > 0 ? a.innerHTML = "合众人寿" : n.search(/huatai/) > 0 ? a.innerHTML = "华泰人寿" : n.search(/jianxin/) > 0 ? a.innerHTML = "建信人寿" : n.search(/minsheng/) > 0 ? a.innerHTML = "民生保险" : n.search(/renbao/) > 0 ? a.innerHTML = "人保健康" : n.search(/rensb/) > 0 ? a.innerHTML = "中国人保寿险" : n.search(/shengming/) > 0 ? a.innerHTML = "富徳生命人寿" : n.search(/taikang/) > 0 ? a.innerHTML = "泰康人寿" : n.search(/taipingyang/) > 0 ? a.innerHTML = "太平洋保险" : n.search(/taiping\//) > 0 ? a.innerHTML = "中国太平" : n.search(/xingfu/) > 0 ? a.innerHTML = "幸福人寿" : n.search(/xinhua/) > 0 ? a.innerHTML = "新华保险" : n.search(/xintai/) > 0 ? a.innerHTML = "信泰保险" : n.search(/yangguang/) > 0 ? a.innerHTML = "阳光人寿" : n.search(/youbang/) > 0 ? a.innerHTML = "友邦保险" : n.search(/zhonghan/) > 0 ? a.innerHTML = "中韩人寿" : n.search(/zhongying/) > 0 ? a.innerHTML = "中英人寿" : n.search(/zhonghong/) > 0 ? a.innerHTML = "中宏人寿" : n.search(/huaxia/) > 0 ? a.innerHTML = "华夏保险" : a.style.cssText = "display:none")
	}
}

function loadPage(e, a) {
	var n = _$(e);
	if (1 == a && "undefined" == typeof useIn && "" == n.children[0].children[a].innerHTML) {
		var t = document.createElement("div");
		t.className = "content-slide", t.style.height = window.innerHeight - n.offsetTop + "px";
		var r = document.createElement("iframe");
		return r.scrolling = "no", r.frameBorder = 0, r.style.cssText = "width:100%;height:100%", r.src = pageUrl[a - 1], t.appendChild(r), void n.children[0].children[a].appendChild(t)
	}
	var i = a - 1,
		s = n.children[0].children[a];
	"" == s.innerHTML && $.ajax({
		url: pageUrl[i],
		type: "GET",
		success: function(e) {
			s.innerHTML = e;
			var a = e.search(/\/*movieContent/),
				t = e.search(/\/*about-content/);
			a > 0 && (s.children[0].style.height = window.innerHeight - n.offsetTop + "px", loadPageScript("movie")), t > 0 && loadPageScript("about")
		},
		error: function(e) {
			console.log(e)
		}
	})
}

function loadPageScript(e, a) {
	if ("movie" == e && scrollMv("movieContent"), "about" == e) {
		for (var n = _$("about-content"), t = n.children.length, r = 0, i = 640, s = 0; t > s; s++) r += parseInt(n.children[s].getAttribute("data-height") * window.innerWidth / i);
		n.parentNode.style.height = r + "px"
	}
}

function _$(e) {
	return document.getElementById(e)
}

function getStyle(e, a) {
	return e.currentStyle ? e.currentStyle[a] : getComputedStyle(e, !1)[a]
}

function cssKw(e, a, n) {
	switch (a) {
		case "width":
		case "height":
		case "padding":
			Math.max(n, 0);
		case "margin":
		case "left":
		case "top":
			if (!e.length) {
				e.style[a] = n + "px";
				break
			}
			for (var t = 0, r = e.length; r > t; t++) e[t].style[a] = n + "px";
			break;
		case "opacity":
			if (!e.length) {
				e.style.filter = "alpha(opacity:" + n + ")", e.style.opacity = n / 100;
				break
			}
			for (var t = 0, r = e.length; r > t; t++) e[t].style.filter = "alpha(opacity:" + n + ")", e[t].style.opacity = n / 100;
			break;
		default:
			if (!e.length) {
				e.style[a] = n;
				break
			}
			for (var t = 0, r = e.length; r > t; t++) e[t].style[a] = n
	}
}

function startMove(e, a, n) {
	var t = e;
	clearInterval(t.timer), t.timer = setInterval(function() {
		var e = !0;
		for (var r in a) {
			var i = 0;
			i = parseInt("opacity" == r ? 100 * parseFloat(getStyle(t, r)) : getStyle(t, r));
			var s = (a[r] - i) / 4;
			s = s > 0 ? Math.ceil(s) : Math.floor(s), cssKw(t, r, i + s), i != a[r] && (e = !1)
		}
		e && (clearInterval(t.timer), n && n())
	}, 30)
}

function countNum() {
	$(".count_getSum").each(function() {
		for (var e = this.children.length, a = 0, n = 0; e > n; n++) a += concatString(this.children[n].innerHTML);
		this.innerHTML = toThousands(a)
	})
}
var p_swiper = null;
! function() {
	for (var e = 0, a = ["webkit", "moz"], n = 0; n < a.length && !window.requestAnimationFrame; ++n) window.requestAnimationFrame = window[a[n] + "RequestAnimationFrame"], window.cancelAnimationFrame = window[a[n] + "CancelAnimationFrame"] || window[a[n] + "CancelRequestAnimationFrame"];
	window.requestAnimationFrame || (window.requestAnimationFrame = function(a, n) {
		var t = (new Date).getTime(),
			r = Math.max(0, 16.7 - (t - e)),
			i = window.setTimeout(function() {
				a(t + r)
			}, r);
		return e = t + r, i
	}), window.cancelAnimationFrame || (window.cancelAnimationFrame = function(e) {
		clearTimeout(e)
	})
}();