!function(){var e={2485:function(e,t){var n;!function(){"use strict";var r={}.hasOwnProperty;function o(){for(var e=[],t=0;t<arguments.length;t++){var n=arguments[t];if(n){var a=typeof n;if("string"===a||"number"===a)e.push(n);else if(Array.isArray(n)){if(n.length){var i=o.apply(null,n);i&&e.push(i)}}else if("object"===a){if(n.toString!==Object.prototype.toString&&!n.toString.toString().includes("[native code]")){e.push(n.toString());continue}for(var l in n)r.call(n,l)&&n[l]&&e.push(l)}}}return e.join(" ")}e.exports?(o.default=o,e.exports=o):void 0===(n=function(){return o}.apply(t,[]))||(e.exports=n)}()},2838:function(e){e.exports=function(){"use strict";function e(t){return e="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},e(t)}function t(e,n){return t=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e},t(e,n)}function n(e,r,o){return n=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],(function(){}))),!0}catch(e){return!1}}()?Reflect.construct:function(e,n,r){var o=[null];o.push.apply(o,n);var a=new(Function.bind.apply(e,o));return r&&t(a,r.prototype),a},n.apply(null,arguments)}function r(e){return function(e){if(Array.isArray(e))return o(e)}(e)||function(e){if("undefined"!=typeof Symbol&&null!=e[Symbol.iterator]||null!=e["@@iterator"])return Array.from(e)}(e)||function(e,t){if(e){if("string"==typeof e)return o(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);return"Object"===n&&e.constructor&&(n=e.constructor.name),"Map"===n||"Set"===n?Array.from(e):"Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)?o(e,t):void 0}}(e)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function o(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,r=new Array(t);n<t;n++)r[n]=e[n];return r}var a=Object.hasOwnProperty,i=Object.setPrototypeOf,l=Object.isFrozen,c=Object.getPrototypeOf,s=Object.getOwnPropertyDescriptor,u=Object.freeze,m=Object.seal,p=Object.create,f="undefined"!=typeof Reflect&&Reflect,d=f.apply,h=f.construct;d||(d=function(e,t,n){return e.apply(t,n)}),u||(u=function(e){return e}),m||(m=function(e){return e}),h||(h=function(e,t){return n(e,r(t))});var g,b=x(Array.prototype.forEach),y=x(Array.prototype.pop),v=x(Array.prototype.push),w=x(String.prototype.toLowerCase),N=x(String.prototype.toString),k=x(String.prototype.match),E=x(String.prototype.replace),T=x(String.prototype.indexOf),_=x(String.prototype.trim),S=x(RegExp.prototype.test),A=(g=TypeError,function(){for(var e=arguments.length,t=new Array(e),n=0;n<e;n++)t[n]=arguments[n];return h(g,t)});function x(e){return function(t){for(var n=arguments.length,r=new Array(n>1?n-1:0),o=1;o<n;o++)r[o-1]=arguments[o];return d(e,t,r)}}function O(e,t,n){var r;n=null!==(r=n)&&void 0!==r?r:w,i&&i(e,null);for(var o=t.length;o--;){var a=t[o];if("string"==typeof a){var c=n(a);c!==a&&(l(t)||(t[o]=c),a=c)}e[a]=!0}return e}function C(e){var t,n=p(null);for(t in e)!0===d(a,e,[t])&&(n[t]=e[t]);return n}function M(e,t){for(;null!==e;){var n=s(e,t);if(n){if(n.get)return x(n.get);if("function"==typeof n.value)return x(n.value)}e=c(e)}return function(e){return console.warn("fallback value for",e),null}}var D=u(["a","abbr","acronym","address","area","article","aside","audio","b","bdi","bdo","big","blink","blockquote","body","br","button","canvas","caption","center","cite","code","col","colgroup","content","data","datalist","dd","decorator","del","details","dfn","dialog","dir","div","dl","dt","element","em","fieldset","figcaption","figure","font","footer","form","h1","h2","h3","h4","h5","h6","head","header","hgroup","hr","html","i","img","input","ins","kbd","label","legend","li","main","map","mark","marquee","menu","menuitem","meter","nav","nobr","ol","optgroup","option","output","p","picture","pre","progress","q","rp","rt","ruby","s","samp","section","select","shadow","small","source","spacer","span","strike","strong","style","sub","summary","sup","table","tbody","td","template","textarea","tfoot","th","thead","time","tr","track","tt","u","ul","var","video","wbr"]),L=u(["svg","a","altglyph","altglyphdef","altglyphitem","animatecolor","animatemotion","animatetransform","circle","clippath","defs","desc","ellipse","filter","font","g","glyph","glyphref","hkern","image","line","lineargradient","marker","mask","metadata","mpath","path","pattern","polygon","polyline","radialgradient","rect","stop","style","switch","symbol","text","textpath","title","tref","tspan","view","vkern"]),R=u(["feBlend","feColorMatrix","feComponentTransfer","feComposite","feConvolveMatrix","feDiffuseLighting","feDisplacementMap","feDistantLight","feFlood","feFuncA","feFuncB","feFuncG","feFuncR","feGaussianBlur","feImage","feMerge","feMergeNode","feMorphology","feOffset","fePointLight","feSpecularLighting","feSpotLight","feTile","feTurbulence"]),I=u(["animate","color-profile","cursor","discard","fedropshadow","font-face","font-face-format","font-face-name","font-face-src","font-face-uri","foreignobject","hatch","hatchpath","mesh","meshgradient","meshpatch","meshrow","missing-glyph","script","set","solidcolor","unknown","use"]),F=u(["math","menclose","merror","mfenced","mfrac","mglyph","mi","mlabeledtr","mmultiscripts","mn","mo","mover","mpadded","mphantom","mroot","mrow","ms","mspace","msqrt","mstyle","msub","msup","msubsup","mtable","mtd","mtext","mtr","munder","munderover"]),H=u(["maction","maligngroup","malignmark","mlongdiv","mscarries","mscarry","msgroup","mstack","msline","msrow","semantics","annotation","annotation-xml","mprescripts","none"]),U=u(["#text"]),z=u(["accept","action","align","alt","autocapitalize","autocomplete","autopictureinpicture","autoplay","background","bgcolor","border","capture","cellpadding","cellspacing","checked","cite","class","clear","color","cols","colspan","controls","controlslist","coords","crossorigin","datetime","decoding","default","dir","disabled","disablepictureinpicture","disableremoteplayback","download","draggable","enctype","enterkeyhint","face","for","headers","height","hidden","high","href","hreflang","id","inputmode","integrity","ismap","kind","label","lang","list","loading","loop","low","max","maxlength","media","method","min","minlength","multiple","muted","name","nonce","noshade","novalidate","nowrap","open","optimum","pattern","placeholder","playsinline","poster","preload","pubdate","radiogroup","readonly","rel","required","rev","reversed","role","rows","rowspan","spellcheck","scope","selected","shape","size","sizes","span","srclang","start","src","srcset","step","style","summary","tabindex","title","translate","type","usemap","valign","value","width","xmlns","slot"]),B=u(["accent-height","accumulate","additive","alignment-baseline","ascent","attributename","attributetype","azimuth","basefrequency","baseline-shift","begin","bias","by","class","clip","clippathunits","clip-path","clip-rule","color","color-interpolation","color-interpolation-filters","color-profile","color-rendering","cx","cy","d","dx","dy","diffuseconstant","direction","display","divisor","dur","edgemode","elevation","end","fill","fill-opacity","fill-rule","filter","filterunits","flood-color","flood-opacity","font-family","font-size","font-size-adjust","font-stretch","font-style","font-variant","font-weight","fx","fy","g1","g2","glyph-name","glyphref","gradientunits","gradienttransform","height","href","id","image-rendering","in","in2","k","k1","k2","k3","k4","kerning","keypoints","keysplines","keytimes","lang","lengthadjust","letter-spacing","kernelmatrix","kernelunitlength","lighting-color","local","marker-end","marker-mid","marker-start","markerheight","markerunits","markerwidth","maskcontentunits","maskunits","max","mask","media","method","mode","min","name","numoctaves","offset","operator","opacity","order","orient","orientation","origin","overflow","paint-order","path","pathlength","patterncontentunits","patterntransform","patternunits","points","preservealpha","preserveaspectratio","primitiveunits","r","rx","ry","radius","refx","refy","repeatcount","repeatdur","restart","result","rotate","scale","seed","shape-rendering","specularconstant","specularexponent","spreadmethod","startoffset","stddeviation","stitchtiles","stop-color","stop-opacity","stroke-dasharray","stroke-dashoffset","stroke-linecap","stroke-linejoin","stroke-miterlimit","stroke-opacity","stroke","stroke-width","style","surfacescale","systemlanguage","tabindex","targetx","targety","transform","transform-origin","text-anchor","text-decoration","text-rendering","textlength","type","u1","u2","unicode","values","viewbox","visibility","version","vert-adv-y","vert-origin-x","vert-origin-y","width","word-spacing","wrap","writing-mode","xchannelselector","ychannelselector","x","x1","x2","xmlns","y","y1","y2","z","zoomandpan"]),P=u(["accent","accentunder","align","bevelled","close","columnsalign","columnlines","columnspan","denomalign","depth","dir","display","displaystyle","encoding","fence","frame","height","href","id","largeop","length","linethickness","lspace","lquote","mathbackground","mathcolor","mathsize","mathvariant","maxsize","minsize","movablelimits","notation","numalign","open","rowalign","rowlines","rowspacing","rowspan","rspace","rquote","scriptlevel","scriptminsize","scriptsizemultiplier","selection","separator","separators","stretchy","subscriptshift","supscriptshift","symmetric","voffset","width","xmlns"]),j=u(["xlink:href","xml:id","xlink:title","xml:space","xmlns:xlink"]),G=m(/\{\{[\w\W]*|[\w\W]*\}\}/gm),V=m(/<%[\w\W]*|[\w\W]*%>/gm),W=m(/\${[\w\W]*}/gm),$=m(/^data-[\-\w.\u00B7-\uFFFF]/),q=m(/^aria-[\-\w]+$/),Y=m(/^(?:(?:(?:f|ht)tps?|mailto|tel|callto|cid|xmpp):|[^a-z]|[a-z+.\-]+(?:[^a-z+.\-:]|$))/i),K=m(/^(?:\w+script|data):/i),X=m(/[\u0000-\u0020\u00A0\u1680\u180E\u2000-\u2029\u205F\u3000]/g),Z=m(/^html$/i),J=function(){return"undefined"==typeof window?null:window};return function t(){var n=arguments.length>0&&void 0!==arguments[0]?arguments[0]:J(),o=function(e){return t(e)};if(o.version="2.4.7",o.removed=[],!n||!n.document||9!==n.document.nodeType)return o.isSupported=!1,o;var a=n.document,i=n.document,l=n.DocumentFragment,c=n.HTMLTemplateElement,s=n.Node,m=n.Element,p=n.NodeFilter,f=n.NamedNodeMap,d=void 0===f?n.NamedNodeMap||n.MozNamedAttrMap:f,h=n.HTMLFormElement,g=n.DOMParser,x=n.trustedTypes,Q=m.prototype,ee=M(Q,"cloneNode"),te=M(Q,"nextSibling"),ne=M(Q,"childNodes"),re=M(Q,"parentNode");if("function"==typeof c){var oe=i.createElement("template");oe.content&&oe.content.ownerDocument&&(i=oe.content.ownerDocument)}var ae=function(t,n){if("object"!==e(t)||"function"!=typeof t.createPolicy)return null;var r=null,o="data-tt-policy-suffix";n.currentScript&&n.currentScript.hasAttribute(o)&&(r=n.currentScript.getAttribute(o));var a="dompurify"+(r?"#"+r:"");try{return t.createPolicy(a,{createHTML:function(e){return e},createScriptURL:function(e){return e}})}catch(e){return console.warn("TrustedTypes policy "+a+" could not be created."),null}}(x,a),ie=ae?ae.createHTML(""):"",le=i,ce=le.implementation,se=le.createNodeIterator,ue=le.createDocumentFragment,me=le.getElementsByTagName,pe=a.importNode,fe={};try{fe=C(i).documentMode?i.documentMode:{}}catch(e){}var de={};o.isSupported="function"==typeof re&&ce&&void 0!==ce.createHTMLDocument&&9!==fe;var he,ge,be=G,ye=V,ve=W,we=$,Ne=q,ke=K,Ee=X,Te=Y,_e=null,Se=O({},[].concat(r(D),r(L),r(R),r(F),r(U))),Ae=null,xe=O({},[].concat(r(z),r(B),r(P),r(j))),Oe=Object.seal(Object.create(null,{tagNameCheck:{writable:!0,configurable:!1,enumerable:!0,value:null},attributeNameCheck:{writable:!0,configurable:!1,enumerable:!0,value:null},allowCustomizedBuiltInElements:{writable:!0,configurable:!1,enumerable:!0,value:!1}})),Ce=null,Me=null,De=!0,Le=!0,Re=!1,Ie=!0,Fe=!1,He=!1,Ue=!1,ze=!1,Be=!1,Pe=!1,je=!1,Ge=!0,Ve=!1,We=!0,$e=!1,qe={},Ye=null,Ke=O({},["annotation-xml","audio","colgroup","desc","foreignobject","head","iframe","math","mi","mn","mo","ms","mtext","noembed","noframes","noscript","plaintext","script","style","svg","template","thead","title","video","xmp"]),Xe=null,Ze=O({},["audio","video","img","source","image","track"]),Je=null,Qe=O({},["alt","class","for","id","label","name","pattern","placeholder","role","summary","title","value","style","xmlns"]),et="http://www.w3.org/1998/Math/MathML",tt="http://www.w3.org/2000/svg",nt="http://www.w3.org/1999/xhtml",rt=nt,ot=!1,at=null,it=O({},[et,tt,nt],N),lt=["application/xhtml+xml","text/html"],ct=null,st=i.createElement("form"),ut=function(e){return e instanceof RegExp||e instanceof Function},mt=function(t){ct&&ct===t||(t&&"object"===e(t)||(t={}),t=C(t),he=he=-1===lt.indexOf(t.PARSER_MEDIA_TYPE)?"text/html":t.PARSER_MEDIA_TYPE,ge="application/xhtml+xml"===he?N:w,_e="ALLOWED_TAGS"in t?O({},t.ALLOWED_TAGS,ge):Se,Ae="ALLOWED_ATTR"in t?O({},t.ALLOWED_ATTR,ge):xe,at="ALLOWED_NAMESPACES"in t?O({},t.ALLOWED_NAMESPACES,N):it,Je="ADD_URI_SAFE_ATTR"in t?O(C(Qe),t.ADD_URI_SAFE_ATTR,ge):Qe,Xe="ADD_DATA_URI_TAGS"in t?O(C(Ze),t.ADD_DATA_URI_TAGS,ge):Ze,Ye="FORBID_CONTENTS"in t?O({},t.FORBID_CONTENTS,ge):Ke,Ce="FORBID_TAGS"in t?O({},t.FORBID_TAGS,ge):{},Me="FORBID_ATTR"in t?O({},t.FORBID_ATTR,ge):{},qe="USE_PROFILES"in t&&t.USE_PROFILES,De=!1!==t.ALLOW_ARIA_ATTR,Le=!1!==t.ALLOW_DATA_ATTR,Re=t.ALLOW_UNKNOWN_PROTOCOLS||!1,Ie=!1!==t.ALLOW_SELF_CLOSE_IN_ATTR,Fe=t.SAFE_FOR_TEMPLATES||!1,He=t.WHOLE_DOCUMENT||!1,Be=t.RETURN_DOM||!1,Pe=t.RETURN_DOM_FRAGMENT||!1,je=t.RETURN_TRUSTED_TYPE||!1,ze=t.FORCE_BODY||!1,Ge=!1!==t.SANITIZE_DOM,Ve=t.SANITIZE_NAMED_PROPS||!1,We=!1!==t.KEEP_CONTENT,$e=t.IN_PLACE||!1,Te=t.ALLOWED_URI_REGEXP||Te,rt=t.NAMESPACE||nt,Oe=t.CUSTOM_ELEMENT_HANDLING||{},t.CUSTOM_ELEMENT_HANDLING&&ut(t.CUSTOM_ELEMENT_HANDLING.tagNameCheck)&&(Oe.tagNameCheck=t.CUSTOM_ELEMENT_HANDLING.tagNameCheck),t.CUSTOM_ELEMENT_HANDLING&&ut(t.CUSTOM_ELEMENT_HANDLING.attributeNameCheck)&&(Oe.attributeNameCheck=t.CUSTOM_ELEMENT_HANDLING.attributeNameCheck),t.CUSTOM_ELEMENT_HANDLING&&"boolean"==typeof t.CUSTOM_ELEMENT_HANDLING.allowCustomizedBuiltInElements&&(Oe.allowCustomizedBuiltInElements=t.CUSTOM_ELEMENT_HANDLING.allowCustomizedBuiltInElements),Fe&&(Le=!1),Pe&&(Be=!0),qe&&(_e=O({},r(U)),Ae=[],!0===qe.html&&(O(_e,D),O(Ae,z)),!0===qe.svg&&(O(_e,L),O(Ae,B),O(Ae,j)),!0===qe.svgFilters&&(O(_e,R),O(Ae,B),O(Ae,j)),!0===qe.mathMl&&(O(_e,F),O(Ae,P),O(Ae,j))),t.ADD_TAGS&&(_e===Se&&(_e=C(_e)),O(_e,t.ADD_TAGS,ge)),t.ADD_ATTR&&(Ae===xe&&(Ae=C(Ae)),O(Ae,t.ADD_ATTR,ge)),t.ADD_URI_SAFE_ATTR&&O(Je,t.ADD_URI_SAFE_ATTR,ge),t.FORBID_CONTENTS&&(Ye===Ke&&(Ye=C(Ye)),O(Ye,t.FORBID_CONTENTS,ge)),We&&(_e["#text"]=!0),He&&O(_e,["html","head","body"]),_e.table&&(O(_e,["tbody"]),delete Ce.tbody),u&&u(t),ct=t)},pt=O({},["mi","mo","mn","ms","mtext"]),ft=O({},["foreignobject","desc","title","annotation-xml"]),dt=O({},["title","style","font","a","script"]),ht=O({},L);O(ht,R),O(ht,I);var gt=O({},F);O(gt,H);var bt=function(e){v(o.removed,{element:e});try{e.parentNode.removeChild(e)}catch(t){try{e.outerHTML=ie}catch(t){e.remove()}}},yt=function(e,t){try{v(o.removed,{attribute:t.getAttributeNode(e),from:t})}catch(e){v(o.removed,{attribute:null,from:t})}if(t.removeAttribute(e),"is"===e&&!Ae[e])if(Be||Pe)try{bt(t)}catch(e){}else try{t.setAttribute(e,"")}catch(e){}},vt=function(e){var t,n;if(ze)e="<remove></remove>"+e;else{var r=k(e,/^[\r\n\t ]+/);n=r&&r[0]}"application/xhtml+xml"===he&&rt===nt&&(e='<html xmlns="http://www.w3.org/1999/xhtml"><head></head><body>'+e+"</body></html>");var o=ae?ae.createHTML(e):e;if(rt===nt)try{t=(new g).parseFromString(o,he)}catch(e){}if(!t||!t.documentElement){t=ce.createDocument(rt,"template",null);try{t.documentElement.innerHTML=ot?ie:o}catch(e){}}var a=t.body||t.documentElement;return e&&n&&a.insertBefore(i.createTextNode(n),a.childNodes[0]||null),rt===nt?me.call(t,He?"html":"body")[0]:He?t.documentElement:a},wt=function(e){return se.call(e.ownerDocument||e,e,p.SHOW_ELEMENT|p.SHOW_COMMENT|p.SHOW_TEXT,null,!1)},Nt=function(t){return"object"===e(s)?t instanceof s:t&&"object"===e(t)&&"number"==typeof t.nodeType&&"string"==typeof t.nodeName},kt=function(e,t,n){de[e]&&b(de[e],(function(e){e.call(o,t,n,ct)}))},Et=function(e){var t,n;if(kt("beforeSanitizeElements",e,null),(n=e)instanceof h&&("string"!=typeof n.nodeName||"string"!=typeof n.textContent||"function"!=typeof n.removeChild||!(n.attributes instanceof d)||"function"!=typeof n.removeAttribute||"function"!=typeof n.setAttribute||"string"!=typeof n.namespaceURI||"function"!=typeof n.insertBefore||"function"!=typeof n.hasChildNodes))return bt(e),!0;if(S(/[\u0080-\uFFFF]/,e.nodeName))return bt(e),!0;var r=ge(e.nodeName);if(kt("uponSanitizeElement",e,{tagName:r,allowedTags:_e}),e.hasChildNodes()&&!Nt(e.firstElementChild)&&(!Nt(e.content)||!Nt(e.content.firstElementChild))&&S(/<[/\w]/g,e.innerHTML)&&S(/<[/\w]/g,e.textContent))return bt(e),!0;if("select"===r&&S(/<template/i,e.innerHTML))return bt(e),!0;if(!_e[r]||Ce[r]){if(!Ce[r]&&_t(r)){if(Oe.tagNameCheck instanceof RegExp&&S(Oe.tagNameCheck,r))return!1;if(Oe.tagNameCheck instanceof Function&&Oe.tagNameCheck(r))return!1}if(We&&!Ye[r]){var a=re(e)||e.parentNode,i=ne(e)||e.childNodes;if(i&&a)for(var l=i.length-1;l>=0;--l)a.insertBefore(ee(i[l],!0),te(e))}return bt(e),!0}return e instanceof m&&!function(e){var t=re(e);t&&t.tagName||(t={namespaceURI:rt,tagName:"template"});var n=w(e.tagName),r=w(t.tagName);return!!at[e.namespaceURI]&&(e.namespaceURI===tt?t.namespaceURI===nt?"svg"===n:t.namespaceURI===et?"svg"===n&&("annotation-xml"===r||pt[r]):Boolean(ht[n]):e.namespaceURI===et?t.namespaceURI===nt?"math"===n:t.namespaceURI===tt?"math"===n&&ft[r]:Boolean(gt[n]):e.namespaceURI===nt?!(t.namespaceURI===tt&&!ft[r])&&!(t.namespaceURI===et&&!pt[r])&&!gt[n]&&(dt[n]||!ht[n]):!("application/xhtml+xml"!==he||!at[e.namespaceURI]))}(e)?(bt(e),!0):"noscript"!==r&&"noembed"!==r&&"noframes"!==r||!S(/<\/no(script|embed|frames)/i,e.innerHTML)?(Fe&&3===e.nodeType&&(t=e.textContent,t=E(t,be," "),t=E(t,ye," "),t=E(t,ve," "),e.textContent!==t&&(v(o.removed,{element:e.cloneNode()}),e.textContent=t)),kt("afterSanitizeElements",e,null),!1):(bt(e),!0)},Tt=function(e,t,n){if(Ge&&("id"===t||"name"===t)&&(n in i||n in st))return!1;if(Le&&!Me[t]&&S(we,t));else if(De&&S(Ne,t));else if(!Ae[t]||Me[t]){if(!(_t(e)&&(Oe.tagNameCheck instanceof RegExp&&S(Oe.tagNameCheck,e)||Oe.tagNameCheck instanceof Function&&Oe.tagNameCheck(e))&&(Oe.attributeNameCheck instanceof RegExp&&S(Oe.attributeNameCheck,t)||Oe.attributeNameCheck instanceof Function&&Oe.attributeNameCheck(t))||"is"===t&&Oe.allowCustomizedBuiltInElements&&(Oe.tagNameCheck instanceof RegExp&&S(Oe.tagNameCheck,n)||Oe.tagNameCheck instanceof Function&&Oe.tagNameCheck(n))))return!1}else if(Je[t]);else if(S(Te,E(n,Ee,"")));else if("src"!==t&&"xlink:href"!==t&&"href"!==t||"script"===e||0!==T(n,"data:")||!Xe[e])if(Re&&!S(ke,E(n,Ee,"")));else if(n)return!1;return!0},_t=function(e){return e.indexOf("-")>0},St=function(t){var n,r,a,i;kt("beforeSanitizeAttributes",t,null);var l=t.attributes;if(l){var c={attrName:"",attrValue:"",keepAttr:!0,allowedAttributes:Ae};for(i=l.length;i--;){var s=n=l[i],u=s.name,m=s.namespaceURI;if(r="value"===u?n.value:_(n.value),a=ge(u),c.attrName=a,c.attrValue=r,c.keepAttr=!0,c.forceKeepAttr=void 0,kt("uponSanitizeAttribute",t,c),r=c.attrValue,!c.forceKeepAttr&&(yt(u,t),c.keepAttr))if(Ie||!S(/\/>/i,r)){Fe&&(r=E(r,be," "),r=E(r,ye," "),r=E(r,ve," "));var p=ge(t.nodeName);if(Tt(p,a,r)){if(!Ve||"id"!==a&&"name"!==a||(yt(u,t),r="user-content-"+r),ae&&"object"===e(x)&&"function"==typeof x.getAttributeType)if(m);else switch(x.getAttributeType(p,a)){case"TrustedHTML":r=ae.createHTML(r);break;case"TrustedScriptURL":r=ae.createScriptURL(r)}try{m?t.setAttributeNS(m,u,r):t.setAttribute(u,r),y(o.removed)}catch(e){}}}else yt(u,t)}kt("afterSanitizeAttributes",t,null)}},At=function e(t){var n,r=wt(t);for(kt("beforeSanitizeShadowDOM",t,null);n=r.nextNode();)kt("uponSanitizeShadowNode",n,null),Et(n)||(n.content instanceof l&&e(n.content),St(n));kt("afterSanitizeShadowDOM",t,null)};return o.sanitize=function(t){var r,i,c,u,m,p=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{};if((ot=!t)&&(t="\x3c!--\x3e"),"string"!=typeof t&&!Nt(t)){if("function"!=typeof t.toString)throw A("toString is not a function");if("string"!=typeof(t=t.toString()))throw A("dirty is not a string, aborting")}if(!o.isSupported){if("object"===e(n.toStaticHTML)||"function"==typeof n.toStaticHTML){if("string"==typeof t)return n.toStaticHTML(t);if(Nt(t))return n.toStaticHTML(t.outerHTML)}return t}if(Ue||mt(p),o.removed=[],"string"==typeof t&&($e=!1),$e){if(t.nodeName){var f=ge(t.nodeName);if(!_e[f]||Ce[f])throw A("root node is forbidden and cannot be sanitized in-place")}}else if(t instanceof s)1===(i=(r=vt("\x3c!----\x3e")).ownerDocument.importNode(t,!0)).nodeType&&"BODY"===i.nodeName||"HTML"===i.nodeName?r=i:r.appendChild(i);else{if(!Be&&!Fe&&!He&&-1===t.indexOf("<"))return ae&&je?ae.createHTML(t):t;if(!(r=vt(t)))return Be?null:je?ie:""}r&&ze&&bt(r.firstChild);for(var d=wt($e?t:r);c=d.nextNode();)3===c.nodeType&&c===u||Et(c)||(c.content instanceof l&&At(c.content),St(c),u=c);if(u=null,$e)return t;if(Be){if(Pe)for(m=ue.call(r.ownerDocument);r.firstChild;)m.appendChild(r.firstChild);else m=r;return(Ae.shadowroot||Ae.shadowrootmod)&&(m=pe.call(a,m,!0)),m}var h=He?r.outerHTML:r.innerHTML;return He&&_e["!doctype"]&&r.ownerDocument&&r.ownerDocument.doctype&&r.ownerDocument.doctype.name&&S(Z,r.ownerDocument.doctype.name)&&(h="<!DOCTYPE "+r.ownerDocument.doctype.name+">\n"+h),Fe&&(h=E(h,be," "),h=E(h,ye," "),h=E(h,ve," ")),ae&&je?ae.createHTML(h):h},o.setConfig=function(e){mt(e),Ue=!0},o.clearConfig=function(){ct=null,Ue=!1},o.isValidAttribute=function(e,t,n){ct||mt({});var r=ge(e),o=ge(t);return Tt(r,o,n)},o.addHook=function(e,t){"function"==typeof t&&(de[e]=de[e]||[],v(de[e],t))},o.removeHook=function(e){if(de[e])return y(de[e])},o.removeHooks=function(e){de[e]&&(de[e]=[])},o.removeAllHooks=function(){de={}},o}()}()},2774:function(e){"use strict";e.exports=function e(t,n){if(t===n)return!0;if(t&&n&&"object"==typeof t&&"object"==typeof n){if(t.constructor!==n.constructor)return!1;var r,o,a;if(Array.isArray(t)){if((r=t.length)!=n.length)return!1;for(o=r;0!=o--;)if(!e(t[o],n[o]))return!1;return!0}if(t.constructor===RegExp)return t.source===n.source&&t.flags===n.flags;if(t.valueOf!==Object.prototype.valueOf)return t.valueOf()===n.valueOf();if(t.toString!==Object.prototype.toString)return t.toString()===n.toString();if((r=(a=Object.keys(t)).length)!==Object.keys(n).length)return!1;for(o=r;0!=o--;)if(!Object.prototype.hasOwnProperty.call(n,a[o]))return!1;for(o=r;0!=o--;){var i=a[o];if(!("_owner"===i&&t.$$typeof||e(t[i],n[i])))return!1}return!0}return t!=t&&n!=n}}},t={};function n(r){var o=t[r];if(void 0!==o)return o.exports;var a=t[r]={exports:{}};return e[r].call(a.exports,a,a.exports,n),a.exports}n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,{a:t}),t},n.d=function(e,t){for(var r in t)n.o(t,r)&&!n.o(e,r)&&Object.defineProperty(e,r,{enumerable:!0,get:t[r]})},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},function(){"use strict";var e=window.wp.element,t=window.wp.i18n,r=window.wp.data,o=window.wp.plugins,a=window.wp.blocks,i=window.wp.components,l=window.wp.blockEditor,c=window.wp.editor,s=window.wp.notices,u=window.lodash,m=(n(2774),window.wp.apiFetch),p=n.n(m);n(2838);var f=window.wp.url,d=window.React,h=window.wp.primitives;(0,d.createElement)(h.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,d.createElement)(h.Path,{d:"M15 4H9c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h6c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm.5 14c0 .3-.2.5-.5.5H9c-.3 0-.5-.2-.5-.5V6c0-.3.2-.5.5-.5h6c.3 0 .5.2.5.5v12zm-4.5-.5h2V16h-2v1.5z"})),(0,d.createElement)(h.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,d.createElement)(h.Path,{d:"M17 4H7c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm.5 14c0 .3-.2.5-.5.5H7c-.3 0-.5-.2-.5-.5V6c0-.3.2-.5.5-.5h10c.3 0 .5.2.5.5v12zm-7.5-.5h4V16h-4v1.5z"})),(0,d.createElement)(h.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,d.createElement)(h.Path,{d:"M20.5 16h-.7V8c0-1.1-.9-2-2-2H6.2c-1.1 0-2 .9-2 2v8h-.7c-.8 0-1.5.7-1.5 1.5h20c0-.8-.7-1.5-1.5-1.5zM5.7 8c0-.3.2-.5.5-.5h11.6c.3 0 .5.2.5.5v7.6H5.7V8z"}));window.wp.hooks;const g="Mobile",b="Tablet",y="Desktop",v={},w=getComputedStyle(document.documentElement);v[g]=w.getPropertyValue("--wp--custom--breakpoint--sm")||"576px",v[b]=w.getPropertyValue("--wp--custom--breakpoint--md")||"768px",v[y]=w.getPropertyValue("--wp--custom--breakpoint--lg")||"1024px";const N={};Object.keys(v).map((e=>{N[e]=e===g?"":`@media (min-width: ${v[e]})`})),(0,t.__)("Mobile","content-blocks-builder"),N[g],(0,t.__)("Tablet","content-blocks-builder"),N[b],(0,t.__)("Desktop","content-blocks-builder"),N[y];var k=window.wp.coreData;function E(n){let{modalTitle:r,title:o,titleHelp:a,setIsModalOpen:l,onSubmit:c=u.noop,onCancel:s=u.noop,className:m}=n;const[p,f]=(0,e.useState)(o),d=()=>{l(!1),f(""),s()};return(0,e.createElement)(i.Modal,{title:r,closeLabel:(0,t.__)("Close","content-blocks-builder"),onRequestClose:()=>d(),overlayClassName:"reusable-blocks-menu-items__convert-modal",className:m},(0,e.createElement)("form",{onSubmit:e=>{e.preventDefault(),c(p),d()}},(0,e.createElement)(i.TextControl,{label:(0,t.__)("Name","content-blocks-builder"),value:p,onChange:f,help:a,className:"title-input"}),(0,e.createElement)(i.Flex,{className:"reusable-blocks-menu-items__convert-modal-actions",justify:"flex-end"},(0,e.createElement)(i.FlexItem,null,(0,e.createElement)(i.Button,{variant:"secondary",onClick:()=>d()},(0,t.__)("Cancel","content-blocks-builder"))),(0,e.createElement)(i.FlexItem,null,(0,e.createElement)(i.Button,{variant:"primary",type:"submit",disabled:!p},(0,t.__)("Save","content-blocks-builder"))))))}var T=n(2485),_=n.n(T);const S=e=>{let t=[];return e&&e.length>0&&e.forEach((e=>{let{name:n,innerBlocks:r,attributes:o}=e;t.push([n,o,S(r)])})),t};(0,o.registerPlugin)("boldblocks-create-block-variation",{render:()=>{var n;const{getSelectedBlock:o}=(0,r.useSelect)((e=>e(l.store)),[]),{getBlockVariations:c}=(0,r.useSelect)((e=>e(a.store)),[]),{updateBlockAttributes:u}=(0,r.useDispatch)(l.store),{createSuccessNotice:m,createErrorNotice:d}=(0,r.useDispatch)(s.store),[h,g]=(0,e.useState)(!1),b=h?o():{},{name:y}=null!=b?b:{},v=y&&null!==(n=c(y))&&void 0!==n?n:[],w=y?`${y} variation ${v.length+1}`:"",N=y?`${y}-variation-${((e=21)=>crypto.getRandomValues(new Uint8Array(e)).reduce(((e,t)=>e+((t&=63)<36?t.toString(36):t<62?(t-26).toString(36).toUpperCase():t>62?"-":"_")),""))(10)}`:"";if(!(0,r.useSelect)((e=>e(k.store).canUser("create","posts")),[]))return null;const T=(0,t.__)("Create block variation","content-blocks-builder");return(0,e.createElement)(e.Fragment,null,(0,e.createElement)(l.BlockSettingsMenuControls,null,(t=>{let{selectedClientIds:n}=t;return 1===n?.length?(0,e.createElement)(i.MenuItem,{label:T,icon:"plus-alt2",onClick:()=>g(!0)},T):null})),h&&(0,e.createElement)(E,{modalTitle:(0,t.sprintf)((0,t.__)("Create a new variation for the block: '%s'","content-blocks-builder"),y),title:w,setIsModalOpen:g,onSubmit:e=>{(e=>{let{variationName:n,blockName:r,title:o,description:i="",iconString:l="",block:c,updateBlockAttributes:s,createSuccessNotice:u,createErrorNotice:m}=e;const d=(0,a.serialize)([c]),h=(e=>{let{blockName:t,variationName:n,title:r,block:o}=e;const{attributes:a={},innerBlocks:i=[]}=null!=o?o:{};return{blockName:t,variation:{name:n,title:r,attributes:a,innerBlocks:S(i)}}})({blockName:r,variationName:n,title:o,block:c});p()({path:"boldblocks/v1/createVariation",method:"POST",data:{title:o,content:d,status:"publish",meta:{boldblocks_variation_block_name:r,boldblocks_variation_name:n,boldblocks_variation_data:JSON.stringify(h),boldblocks_variation_icon:l,boldblocks_variation_description:i}}}).then((e=>{const{success:o,data:i,post:l}=e;var p;o?(u(i||(0,t.__)("Your variation has been created successfully!","content-blocks-builder"),{type:"snackbar",actions:[{label:(0,t.__)("Edit variation","content-blocks-builder"),url:(p=l.id,(0,f.addQueryArgs)(`post.php?post=${p}&action=edit`))}]}),(0,a.registerBlockVariation)(r,h.variation),setTimeout((()=>{s(c.clientId,{className:_()(c?.attributes?.className,`is-style-${n.replace("/","-")}`)})}),0)):m(i||"Request failed!",{type:"snackbar"})})).catch((e=>{m(e.message,{type:"snackbar"})}))})({title:e,block:b,blockName:y,variationName:N,updateBlockAttributes:u,createSuccessNotice:m,createErrorNotice:d})},className:"cbb-block-modal"}))}}),(0,o.registerPlugin)("boldblocks-variations",{render:()=>{const{getCurrentPostType:t}=(0,r.useSelect)((e=>e(c.store)),[]),n=(()=>{const t=(0,e.useRef)(!0);return(0,e.useEffect)((()=>{t.current=!1}),[]),t.current})(),{setNavigationMode:o}=(0,r.useDispatch)(l.store);return"boldblocks_variation"===t()&&n&&o(!0),null}})}()}();