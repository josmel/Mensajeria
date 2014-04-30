// Generated by CoffeeScript 1.6.3
(function() {
  (function($) {
    var jqPlaceholder;
    jqPlaceholder = (function() {
      function jqPlaceholder(options) {
        var opt;
        opt = {
          inpt: ":input[placeholder]",
          ignore: "input[type=hidden]",
          classPlaceholder: "plc-label",
          charguePlaceholder: null
        };
        this.settings = $.extend(opt, options);
        this.test = document.createElement("input");
        this.state = this.test.placeholder !== void 0;
        this._init();
      }

      jqPlaceholder.prototype._init = function() {
        return this._dipatch();
      };

      jqPlaceholder.prototype._dipatch = function() {
        var cond, cssEl, element, paddEl, parentEl, place, posEl, propEl, settings, state, _this;
        _this = this;
        settings = this.settings;
        state = this.state;
        this.elements = $(settings.inpt).not(settings.ignore);
        cond = "";
        element = null;
        place = null;
        paddEl = "";
        posEl = null;
        cssEl = null;
        parentEl = null;
        propEl = null;
        if (!state) {
          return this.elements.each(function(index, value) {
            element = $(value);
            cond = element.val() === "" ? "block" : "none";
            parentEl = element.parent();
            parentEl.css("position", "relative");
            paddEl = _this._getPadding(element);
            posEl = _this._getPosition(element);
            propEl = _this._getProportions(element);
            place = $("<label />", {
              "html": element.attr("placeholder"),
              "class": settings.classPlaceholder,
              "display": cond
            });
            cssEl = {
              "position": "absolute",
              "z-index": "99"
            };
            cssEl = $.extend(cssEl, posEl, paddEl, propEl);
            place.css(cssEl);
            _this._bindEvents.call(place, element, _this);
            parentEl.append(place);
            return settings.charguePlaceholder && settings.charguePlaceholder(element, place);
          });
        }
      };

      jqPlaceholder.prototype._bindEvents = function(element, inst) {
        var el, settings;
        el = this;
        settings = inst.settings;
        el.on("click", function(e) {
          e.preventDefault();
          el.hide();
          return element.focus();
        });
        return element.on("blur", function(e) {
          if (element.val() === "") {
            return el.show();
          }
        });
      };

      jqPlaceholder.prototype._getPosition = function(ele) {
        var mLeft, mTop, posEl;
        mTop = parseFloat(ele.css("margin-top").replace("px", ""));
        mLeft = parseFloat(ele.css("margin-left").replace("px", ""));
        posEl = ele.position();
        mTop = posEl.top + mTop;
        mLeft = posEl.left + mLeft;
        return {
          "left": mLeft,
          "top": mTop
        };
      };

      jqPlaceholder.prototype._getPadding = function(ele) {
        var pBottom, pLeft, pRight, pTop;
        pTop = ele.css("padding-top");
        pLeft = ele.css("padding-left");
        pBottom = ele.css("padding-bottom");
        pRight = ele.css("padding-right");
        return {
          "padding-top": pTop,
          "padding-left": pLeft,
          "padding-bottom": pBottom,
          "padding-right": pRight
        };
      };

      jqPlaceholder.prototype._getProportions = function(ele) {
        var hght, wdth;
        wdth = ele.width();
        hght = ele.height();
        return {
          "width": wdth,
          "height": hght
        };
      };

      return jqPlaceholder;

    })();
    $.extend({
      jqPlaceholder: function(json) {
        new jqPlaceholder(json);
      }
    });
  })(jQuery);

}).call(this);