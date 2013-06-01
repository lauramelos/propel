/*
  text-shadow for MSIE
  http://asamuzak.jp
*/

var isMSIE = /*@cc_on!@*/false;
var ieVersion = (function(reg) { return isMSIE && navigator.userAgent.match(reg) ? RegExp.$1 * 1 : null; })(/MSIE\s([0-9]+[\.0-9]*)/);

function addEvent(target, type, listener) {
  target.addEventListener ? target.addEventListener(type, listener, false) :
  target.attachEvent ? target.attachEvent('on' + type, function() { listener.call(target, window.event) }) :
  target['on' + type] = function(e) { listener.call(target, e || window.event) };
}

var cNum = (function(n) { return function() { return n++; }})(0);

function showElm(eId) {
  var elm = document.getElementById(eId);
  elm && (elm.style.visibility = 'visible');
}
function hideElm(eId) {
  var elm = document.getElementById(eId);
  elm && (elm.style.visibility = 'hidden');
}

function textShadowForMSIE() {
  var ieShadowSettings = function() {
    return isMSIE ? [
      // ここ（return [];内）にtext-shadowを適用させるセレクタの配列を記述
      // セレクタ毎に「カンマ区切り」で配列を追加（カンマを忘れるとエラー発生）
      // Write your text-shadow settings here, like below.
      // { sel : 'h1', shadow : '2px 2px 2px gray' },
      // { sel : 'em', shadow : '1px 1px 1px rgb(0, 100, 100)' }
      { sel : '.home-slide-item h1', shadow : '0px 3px 6px rgba(0, 0, 0, 0.5)' },
      { sel : '.home-slide-item .caption', shadow : '0px 3px 10px rgba(0, 0, 0, 0.5)' }

    ] : null;
  };
  var getCompStyle = function(elm) {
    return isMSIE && ieVersion < 9 ? elm.currentStyle : document.defaultView.getComputedStyle(elm, '');
  };
  var getPrevSibling = function(pElm) {
    return pElm.nodeType == 1 ? pElm : (pElm = pElm.previousSibling, pElm != null ? getPrevSibling(pElm) : null);
  };
  var getGeneralObj = function(pElm) {
    var arr = [];
    for((pElm = pElm.previousSibling) && pElm.nodeType == 1 && (arr[arr.length] = pElm); pElm != null;) {
      (pElm = pElm.previousSibling) && pElm.nodeType == 1 && (arr[arr.length] = pElm);
    }
    return arr;
  };
  var getAncestObj = function(pElm) {
    var arr = [];
    if(pElm = pElm.parentNode) {
      for(arr[arr.length] = pElm; pElm.nodeName.toLowerCase() != "body";) {
        (pElm = pElm.parentNode) && (arr[arr.length] = pElm);
      }
    }
    return arr;
  };
  var convPercentTo256 = function(cProf) {
    if(cProf.match(/(rgba?)\(\s*([0-9\.]+%?\s*,\s*[0-9\.]+%?\s*,\s*[0-9\.]+%?)\s*(,\s*[01]?[\.0-9]*)?\s*\)/)) {
      for(var cType = RegExp.$1, arr = RegExp.$2.split(/,/), aCh = (RegExp.$3 || ''), rgbArr = [], i = 0, l = arr.length; i < l; i++) {
        arr[i].match(/([0-9\.]+)%/) && (arr[i] = Math.round(RegExp.$1 * 255 / 100));
        rgbArr[rgbArr.length] = arr[i];
      }
      return cType + '(' + rgbArr.join(',') + aCh + ')';
    }
  };
  var convUnitToPx = function(sUnit, obj) {
    var getUnitRatio = function(sUnit) {
      var elm, val, dId = cNum(), dBox = document.createElement('div'), dBody = document.getElementsByTagName('body')[0];
      dBox.id = 'dummyDiv' + dId;  dId++;
      dBox.style.width = sUnit;
      dBox.style.height = 0;
      dBox.style.visibility = 'hidden';
      dBody.appendChild(dBox);
      elm = document.getElementById(dBox.id);
      val = Math.abs(elm.getBoundingClientRect().right - elm.getBoundingClientRect().left);
      dBody.removeChild(elm);
      return val;
    };
    if(sUnit.match(/^0(em|ex|px|cm|mm|in|pt|pc)?$/)) {
      return 0;
    }
    else if(sUnit.match(/^(\-?[0-9\.]+)px$/)) {
      return RegExp.$1 * 1;
    }
    else if(sUnit.match(/^(\-?[0-9\.]+)(cm|mm|in|pt|pc)$/)) {
      return RegExp.$1 * 1 >= 0 ? getUnitRatio(sUnit) : getUnitRatio((RegExp.$1 * -1) + RegExp.$2) * -1;
    }
    else if(sUnit.match(/^(\-?[0-9\.]+)(em|ex)$/)) {
      var val = (getUnitRatio(sUnit) / getUnitRatio('1em')), arr = getAncestObj(obj), dRoot = document.getElementsByTagName('html')[0], fSize = [];
      arr.unshift(obj);  arr[arr.length] = dRoot;
      for(var i = 0, l = arr.length; i < l; i++) {
        fSize[fSize.length] = getCompStyle(arr[i]).fontSize;
      }
      for(i = 0, l = fSize.length; i < l; i++) {
        if(fSize[i].match(/^([0-9\.]+)%$/)) {
          val *= (RegExp.$1 / 100);
        }
        else if(fSize[i].match(/^[0-9\.]+(em|ex)$/)) {
          val *= (getUnitRatio(fSize[i]) / getUnitRatio('1em'));
        }
        else if(fSize[i].match(/^smaller$/)) {
          val /= 1.2;
        }
        else if(fSize[i].match(/^larger$/)) {
          val *= 1.2;
        }
        else if(fSize[i].match(/^([0-9\.]+)(px|cm|mm|in|pt|pc)$/)) {
          val *= getUnitRatio(fSize[i]);
          break;
        }
        else if(fSize[i].match(/^xx\-small$/)) {
          val *= (getUnitRatio(getCompStyle(dRoot).fontSize) / 1.728);
          break;
        }
        else if(fSize[i].match(/^x\-small$/)) {
          val *= (getUnitRatio(getCompStyle(dRoot).fontSize) / 1.44);
          break;
        }
        else if(fSize[i].match(/^small$/)) {
          val *= (getUnitRatio(getCompStyle(dRoot).fontSize) / 1.2);
          break;
        }
        else if(fSize[i].match(/^medium$/)){
          val *= getUnitRatio(getCompStyle(dRoot).fontSize);
          break;
        }
        else if(fSize[i].match(/^large$/)) {
          val *= (getUnitRatio(getCompStyle(dRoot).fontSize) * 1.2);
          break;
        }
        else if(fSize[i].match(/^x\-large$/)) {
          val *= (getUnitRatio(getCompStyle(dRoot).fontSize) * 1.44);
          break;
        }
        else if(fSize[i].match(/^xx\-large$/)) {
          val *= (getUnitRatio(getCompStyle(dRoot).fontSize) * 1.728);
          break;
        }
        else if(fSize[i].match(/^([0-9\.]+)([a-z]+)/)) {
          val *= getUnitRatio(fSize[i]);
          break;
        }
        else {
          break;
        }
      }
      return Math.round(val);
    }
  };
  var removeDupFunc = function(fStr) {
    for(var arr = fStr.replace(/\s+/, '').split(/;/), fArr = [], bool, i = 0, l = arr.length; i < l; i++) {
      bool = true;
      for(var j = i; j < l; j++) {
        i != j && arr[i] == arr[j] && (bool = false);
      }
      bool && arr[i] != '' && (fArr[fArr.length] = arr[i]);
    }
    return fArr.join(';') + ';';
  }
  var revArr = function(arr) {
    for(var rArr = [], i = 0, l = arr.length; i < l; i++) {
      rArr.unshift(arr[i]);
    }
    return rArr;
  };
  var setShadow = function(tObj) {
    var setShadowNodeColor = function(elm) {
      for(var arr = elm.childNodes, i = 0, l = arr.length; i < l; i++) {
        if(arr[i].nodeType == 1) {
          !arr[i].hasChildNodes() ? arr[i].style.visibility = 'hidden' : (arr[i].style.color = elm.style.color, setShadowNodeColor(arr[i]));
        }
      }
    };
    var hideAncestShadow = function(oElm, pElm) {
      for(var arr = pElm.childNodes, i = 0, l = arr.length; i < l; i++) {
        if(arr[i].hasChildNodes()) {
          arr[i].nodeName.toLowerCase() == oElm.tagName.toLowerCase() && arr[i].firstChild == oElm.firstChild ? arr[i].style.visibility = 'hidden' : hideAncestShadow(oElm, arr[i]);
        }
      }
    };
    var boolShadowChild = function(elm) {
      for(var bool = true, arr = getAncestObj(elm), i = 0, l = arr.length; i < l; i++) {
        if(arr[i].tagName.toLowerCase() == 'span' && arr[i].className.match(/dummyShadow/)) {
          bool = false;
          break;
        }
      }
      return bool;
    };
    if(tObj.shadow != 'invalid') {
      for(var arr = [], nArr = tObj.elm.childNodes, bool = false, i = 0, l = nArr.length; i < l; i++) {
        if(nArr[i].nodeName.toLowerCase() == 'span' && nArr[i].className.match(/dummyShadow/)) {
          nArr[i].className.match(/hasImp/) && (bool = true);
          arr[arr.length] = nArr[i].id;
        }
      }
      if(bool == false || tObj.hasImp == true) {
        var mOver = tObj.elm.getAttribute('onmouseover') || '';
        var mOut = tObj.elm.getAttribute('onmouseout') || '';
        mOver != '' && !mOver.match(/;$/) && (mOver += ';');
        mOut != '' && !mOut.match(/;$/) && (mOut += ';');
        for(i = 0, l = arr.length; i < l; i++) {
          if(tObj.ePseudo == 'hover' && tObj.shadow == 'none') {
            mOver += "hideElm('" + arr[i] + "');";
            mOut += "showElm('" + arr[i] + "');";
          }
          else if(!(tObj.ePseudo == 'hover' && tObj.shadow != 'none')) {
            tObj.elm.removeChild(document.getElementById(arr[i]));
          }
        }
        tObj.ePseudo == 'hover' && tObj.shadow == 'none' && (tObj.elm.setAttribute('onmouseover', mOver), tObj.elm.setAttribute('onmouseout', mOut));
        for(var aBg, arr = getAncestObj(tObj.elm), i = 0, l = arr.length; i < l; i++) {
          aBg == null && (getCompStyle(arr[i]).backgroundColor != 'transparent' || getCompStyle(arr[i]).backgroundImage != 'none') && (aBg = arr[i]);
          for(var cArr = arr[i].childNodes, j = 0, k = cArr.length; j < k; j++) {
            cArr[j].nodeType == 1 && cArr[j].nodeName.toLowerCase() == 'span' && cArr[j].className.match(/dummyShadow/) && hideAncestShadow(tObj.elm, document.getElementById(cArr[j].id));
          }
        }
        tObj.shadow != 'none' && tObj.shadow.length > 1 && (getCompStyle(tObj.elm).backgroundColor != 'transparent' || getCompStyle(tObj.elm).backgroundImage != 'none') && (tObj.shadow = revArr(tObj.shadow));
        if(tObj.shadow == 'none' && tObj.ePseudo != 'hover') {
          for(var arr = tObj.elm.parentNode.childNodes, i = 0, l = arr.length; i < l; i++) {
            if(arr[i].nodeName.toLowerCase() == 'span' && arr[i].className == 'dummyShadow') {
              getCompStyle(tObj.elm).display == 'inline-block' && (tObj.elm.style.display = 'inline');
              getCompStyle(tObj.elm).position == 'relative' && (tObj.elm.style.position = 'static');
              break;
            }
          }
        }
        if(tObj.shadow != 'none' && nArr.length != 0 && boolShadowChild(tObj.elm)) {
          for(var hArr = [], clNode = tObj.elm.cloneNode(true), arr = clNode.childNodes, i = 0, l = arr.length; i < l; i++) {
            arr[i] != null && arr[i].hasChildNodes() && arr[i].nodeName.toLowerCase() == 'span' && arr[i].className.match(/dummyShadow/) && (hArr[hArr.length] = arr[i].id, clNode.removeChild(arr[i]));
          }
          var sNode = clNode.innerHTML;
          ieVersion == 9 && (sNode = sNode.replace(/\n/, ' '));
          ieVersion == 8 && (tObj.elm.innerHTML = tObj.elm.innerHTML);
          for(i = 0, l = tObj.shadow.length; i < l; i++) {
            var pxRad = convUnitToPx(tObj.shadow[i].z, tObj.elm);
            var xPos = convUnitToPx(tObj.shadow[i].x, tObj.elm) - pxRad + convUnitToPx(getCompStyle(tObj.elm).paddingLeft, tObj.elm);
            var yPos = convUnitToPx(tObj.shadow[i].y, tObj.elm) - pxRad + convUnitToPx(getCompStyle(tObj.elm).paddingTop, tObj.elm);
            if(ieVersion == 7 && pxRad == 0) {
              xPos >= 0 && (xPos -= 1);
              yPos >= 0 && (yPos -= 1);
            }
            var sColor = tObj.shadow[i].cProf || getCompStyle(tObj.elm).color;
            var sOpacity = 0.6;  // デフォルトの透過度
            tObj.shadow[i].cProf != null && tObj.shadow[i].cProf.match(/rgba\(\s*([0-9]+\s*,\s*[0-9]+\s*,\s*[0-9]+)\s*,\s*([01]?[\.0-9]*)\)/) && (sColor = 'rgb(' + RegExp.$1 + ')', sOpacity = (RegExp.$2 * 1));
            var sBox = document.createElement('span');
            sBox.id = 'dummyShadow' + sId;  sId++;
            sBox.className = (tObj.hasImp == true) ? 'dummyShadow hasImp' : 'dummyShadow';
            sBox.style.display = 'block';
            sBox.style.position = 'absolute';
            sBox.style.left = xPos + 'px';
            sBox.style.top = yPos + 'px';
            sBox.style.width = '100%';
            sBox.style.color = sColor;
            sBox.style.filter = 'progid:DXImageTransform.Microsoft.Blur(PixelRadius=' + pxRad + ', MakeShadow=false, ShadowOpacity=' + sOpacity + ')';
            sBox.style.zIndex = -(i + 1);
            sBox.innerHTML = sNode;
            if(getCompStyle(tObj.elm).display == 'inline') {
              tObj.elm.style.display = 'inline-block';
            }
            if(!(getCompStyle(tObj.elm).position == 'absolute' || getCompStyle(tObj.elm).position == 'fixed')) {
              tObj.elm.style.position = 'relative';
              ieVersion == 7 && (tObj.elm.style.top = getCompStyle(tObj.elm).paddingTop);
            }
            if(getCompStyle(tObj.elm).backgroundColor != 'transparent' || getCompStyle(tObj.elm).backgroundImage != 'none') {
              getCompStyle(tObj.elm).zIndex != ('auto' || null) ? (sBox.style.zIndex = tObj.elm.style.zIndex) : (tObj.elm.style.zIndex = sBox.style.zIndex = -1);
            }
            if(aBg && aBg.tagName.toLowerCase() != 'body') {
              tObj.elm.style.zIndex = 1; sBox.style.zIndex = -1;
            }
            ieVersion == 7 && getCompStyle(tObj.elm).lineHeight.match(/^([0-9\.]+)(em|ex|px|cm|mm|in|pt|pc|%)?$/) && (tObj.elm.style.minHeight = !RegExp.$2 ? convUnitToPx(RegExp.$1 + 'em', tObj.elm) : RegExp.$2 == '%' ? convUnitToPx((RegExp.$1 / 100) + 'em', tObj.elm) : convUnitToPx(RegExp.$1 + RegExp.$2, tObj.elm));
            tObj.elm.appendChild(sBox);
            if(tObj.ePseudo == 'hover') {
              sBox.style.visibility = 'hidden';
              mOver = tObj.elm.getAttribute('onmouseover') || '';
              mOut = tObj.elm.getAttribute('onmouseout') || '';
              mOver != '' && !mOver.match(/;$/) && (mOver += ';');
              mOut != '' && !mOut.match(/;$/) && (mOut += ';');
              mOver += ("showElm('" + sBox.id + "');");
              mOut += ("hideElm('" + sBox.id + "');");
              if(hArr.length > 0) {
                for(j = 0, k = hArr.length; j < k; j++) {
                  var hElm = document.getElementById(hArr[j]);
                  if(hElm) {
                    mOver += ("hideElm('" + hElm.id + "');");
                    mOut += ("showElm('" + hElm.id + "');");
                  }
                }
              }
              tObj.elm.setAttribute('onmouseover', removeDupFunc(mOver));
              tObj.elm.setAttribute('onmouseout', removeDupFunc(mOut));
            }
            setShadowNodeColor(document.getElementById(sBox.id));
          }
        }
      }
    }
  };
  var getTargetObj = function(sObj) {
    var getObjType = function(oElm) {
      return oElm.id != null ? document.getElementById(oElm.id) : document.getElementsByTagName(oElm.elm);
    };
    var compareObj = function(rObj, cObj) {
      return ((rObj.id != null && cObj.id != null && rObj.id == cObj.id) || rObj.id == null || cObj.id == null) &&
         (rObj.elm == cObj.tagName.toLowerCase() || rObj.elm == '*') &&
         ((rObj.eClass != null && cObj.className != null && rObj.eClass == cObj.className) || rObj.eClass == null) ? true : false;
    };
    var elm = getObjType(sObj.tElm);
    if(elm) {
      var tObj = { elm : null, ePseudo : sObj.tElm.ePseudo, shadow : sObj.sVal, hasImp : sObj.sImp };
      if(sObj.type == 'single') {
        if(elm.id && compareObj(sObj.tElm, elm)) {
          tObj.elm = elm;
          setShadow(tObj);
        }
        else {
          for(var i = 0, l = elm.length; i < l; i++) {
            if(compareObj(sObj.tElm, elm[i])) {
              tObj.elm = elm[i];
              setShadow(tObj);
            }
          }
        }
      }
      else if(sObj.type == 'descend' || sObj.type == 'child') {
        if(elm.id && compareObj(sObj.tElm, elm)) {
          var pElm = elm.parentNode;
          if(compareObj(sObj.rElm, pElm)) {
            tObj.elm = elm;
            setShadow(tObj);
          }
          else if(sObj.type == 'descend') {
            for(var arr = getAncestObj(pElm), i = 0, l = arr.length; i < l; i++) {
              if(compareObj(sObj.rElm, arr[i])) {
                tObj.elm = elm;
                setShadow(tObj);
                break;
              }
            }
          }
        }
        else {
          for(var i = 0, l = elm.length; i < l; i++) {
            if(compareObj(sObj.tElm, elm[i])) {
              var pElm = elm[i].parentNode;
              if(compareObj(sObj.rElm, pElm)) {
                tObj.elm = elm[i];
                setShadow(tObj);
              }
              else if(sObj.type == 'descend') {
                for(var arr = getAncestObj(pElm), j = 0, k = arr.length; j < k; j++) {
                  if(compareObj(sObj.rElm, arr[j])) {
                    tObj.elm = elm[i];
                    setShadow(tObj);
                    break;
                  }
                }
              }
            }
          }
        }
      }
      else if(sObj.type == 'adjacent' || sObj.type == 'general') {
        if(elm.id && compareObj(sObj.tElm, elm) && elm.previousSibling != null) {
          var pElm = getPrevSibling(elm.previousSibling);
          if(pElm && compareObj(sObj.rElm, pElm)) {
            tObj.elm = elm;
            setShadow(tObj);
          }
          else if(sObj.type == 'general' && pElm.previousSibling != null) {
            for(var arr = getGeneralObj(pElm), i = 0, l = arr.length; i < l; i++) {
              if(compareObj(sObj.rElm, arr[i])) {
                tObj.elm = elm;
                setShadow(tObj);
                break;
              }
            }
          }
        }
        else {
          for(var i = 0, l = elm.length; i < l; i++) {
            if(compareObj(sObj.tElm, elm[i]) && elm[i].previousSibling != null) {
              var pElm = getPrevSibling(elm[i].previousSibling);
              if(pElm && compareObj(sObj.rElm, pElm)) {
                tObj.elm = elm[i];
                setShadow(tObj);
              }
              else if(sObj.type == 'general' && pElm.previousSibling != null) {
                for(var arr = getGeneralObj(pElm), j = 0, k = arr.length; j < k; j++) {
                  if(compareObj(sObj.rElm, arr[j])) {
                    tObj.elm = elm[i];
                    setShadow(tObj);
                    break;
                  }
                }
              }
            }
          }
        }
      }
    }
  };
  var getShadowValue = function(shadow) {
    if(shadow.match(/none/)) {
      return 'none';
    }
    else {
      for(var val = [], arr = shadow.match(/((rgba?\(\s*[0-9\.]+%?\s*,\s*[0-9\.]+%?\s*,\s*[0-9\.]+%?\s*(,\s*[01]?[\.0-9]*\s*)?\)|#[0-9a-fA-F]{3,6}|[a-zA-Z]+)\s)?(\-?[0-9\.]+(em|ex|px|cm|mm|in|pt|pc)?\s*){2,3}(rgba?\(\s*[0-9\.]+%?\s*,\s*[0-9\.]+%?\s*,\s*[0-9\.]+%?\s*(,\s*[01]?[\.0-9]*\s*)?\)|#[0-9a-fA-F]{3,6}|[a-zA-Z]+)?/g), i = 0, l = arr.length; i < l; i++) {
        val[i] = { x : '0', y : '0', z : '0', cProf : null };
        var uArr = arr[i].match(/\-?[0-9\.]+(em|ex|px|cm|mm|in|pt|pc)?\s+\-?[0-9\.]+(em|ex|px|cm|mm|in|pt|pc)?(\s+[0-9\.]+(em|ex|px|cm|mm|in|pt|pc)?)?/);
        if(uArr = uArr[0].split(/\s+/), uArr[0].match(/^(\-?[0-9\.]+(em|ex|px|cm|mm|in|pt|pc)?)$/) && uArr[1].match(/^(\-?[0-9\.]+(em|ex|px|cm|mm|in|pt|pc)?)$/)) {
          uArr.length >= 2 && (val[i].x = uArr[0], val[i].y = uArr[1]);
          uArr.length == 3 && uArr[2].match(/^([0-9\.]+(em|ex|px|cm|mm|in|pt|pc)?)$/) && (val[i].z = uArr[2]);
          arr[i].match(/%/) && (arr[i] = convPercentTo256(arr[i]));
          arr[i].match(/^(rgba?\(\s*[0-9]+\s*,\s*[0-9]+\s*,\s*[0-9]+\s*(,\s*[01]?[\.0-9]*\s*)?\)|[a-zA-Z]+)/) ? (val[i].cProf = RegExp.$1) :
          arr[i].match(/\s(rgba?\(\s*[0-9]+\s*,\s*[0-9]+\s*,\s*[0-9]+\s*(,\s*[01]?[\.0-9]*\s*)?\)|#[0-9a-fA-F]{3,6}|[a-zA-Z]+)$/) && (val[i].cProf = RegExp.$1);
        }
        else {
          val = 'invalid';
          break;
        }
      }
      return val;
    }
  };
  var getSelectorObj = function(sel) {
    if(sel != null) {
      var obj = { elm : '*', id : null, eClass : null, ePseudo : null };
      sel.match(/^([a-zA-Z\*]{1}[a-zA-Z0-9]*)/) && (obj.elm = RegExp.$1);
      sel.match(/#([a-zA-Z_]{1}[a-zA-Z0-9_\-]*)/) && (obj.id = RegExp.$1);
      sel.match(/\.([a-zA-Z_]{1}[a-zA-Z0-9_\-]*)/) && (obj.eClass = RegExp.$1);
      sel.match(/:([a-z]{1}[a-z0-9\(\)\-]+)/) && (obj.ePseudo = RegExp.$1);
      return obj;
    }
    if(sel == null) {
      return null;
    }
  };
  var distinctSelector = function(sel) {
    var arr = [];
    sel.match(/^([a-zA-Z0-9#\.:_\-]+)$/) ? arr = ['single', RegExp.$1, null] :
    sel.match(/^([a-zA-Z0-9#\.:_\-]+)\s+([a-zA-Z0-9#\.:_\-]+)$/) ? arr = ['descend', RegExp.$2, RegExp.$1] :
    sel.match(/^([a-zA-Z0-9#\.:_\-]+)\s*>\s*([a-zA-Z0-9#\.:_\-]+)$/) ? arr = ['child', RegExp.$2, RegExp.$1] :
    sel.match(/^([a-zA-Z0-9#\.:_\-]+)\s*\+\s*([a-zA-Z0-9#\.:_\-]+)$/) ? arr = ['adjacent', RegExp.$2, RegExp.$1] :
    sel.match(/^([a-zA-Z0-9#\.:_\-]+)\s*~\s*([a-zA-Z0-9#\.:_\-]+)$/) && (arr = ['general', RegExp.$2, RegExp.$1]);
    return arr;
  };
  for(var arr = ieShadowSettings(), sId = cNum(), i = 0, l = arr.length; i < l; i++) {
    for(var sSel = arr[i].sel.split(/,/), sReg = /^\s*([a-zA-Z0-9#\.:_\-\s>\+~]+)\s*$/, j = 0, k = sSel.length; j < k; j++) {
      sSel[j].match(sReg) && (sSel[j] = RegExp.$1);
      var sArr = distinctSelector(sSel[j]);
      var sObj = { type : null, tElm : null, rElm : null, sVal : null, sImp : null };
      sObj.type = sArr[0];
      sObj.tElm = getSelectorObj(sArr[1]);
      sObj.rElm = getSelectorObj(sArr[2]);
      sObj.sVal = getShadowValue(arr[i].shadow);
      sObj.sImp = arr[i].shadow.match(/important/) ? true : false;
      getTargetObj(sObj);
    }
  }
}
addEvent(window, 'load', function() { ieVersion >= 7 && ieVersion <= 9 && textShadowForMSIE(); });