function trace(text,o){
	o=o==1?{clear:1}:o?o:{};
	var d=document.getElementById('vik-trace')||(function(){
		var d=document.createElement("DIV");
		d.id="vik-trace";
		d.style.cssText='position:fixed;max-width:600px;font-size:12px;line-height:14px;white-space:pre;z-index:1000;font-family:monospace;top:3px;right:0px;border:solid 5px #B7BEC4;background-color:#E7ECF0;padding:5px';
		document.body.insertBefore(d,document.body.firstChild);
		var x=document.createElement("div");
		x.style.cssText="position:absolute;top:-8px;right:-4px;color:white;font-size:9px;cursor:pointer;";
		x.innerHTML='x';
		x.onclick=function(){d.parentNode.removeChild(d);return false;};
		d.appendChild(x);
		c=document.createElement("div");
		c.style.cssText='overflow:auto;padding-right: 10px;padding-bottom:10px;position:relative;-moz-tab-size:4;-o-tab-size:4;';
		c.style.maxHeight=((window.innerHeight||window.outerHeight||700)-40)+'px';
		d.appendChild(c);
		return d;}
	)();
	d.style.display='block';
	if(o.fix === true || o.fix === 1) d.style.position="fixed";
	if(o.fix === false || o.fix === 0) d.style.position="absolute";
	var c=d.lastChild;
	if(o.clear) c.innerHTML='';
	var t=document.createElement('div');
	if(text === null){
		t.appendChild(document.createTextNode('null'));
	}else if(typeof text=='object' && text.nodeName){
		t.appendChild(text);
	}else{
		t.appendChild(document.createTextNode(text));
	}
	c.appendChild(t);
	c.scrollTop=t.offsetTop;
}

function print_r(trg,ret){var data='';for(i in trg){data+=i+' => '+trg[i]+'<br />';}if(ret){return data;}else{trace(data,1);}}

function debug_print_backtrace(){
	
	try{
		throw new Error();
	}catch(e){
		var traceArr = [];
		if(e.stacktrace){
			traceArr = e.stacktrace.split('\n');
			traceArr.shift();
			traceArr.shift();
		}
		else if(e.stack){
			traceArr = e.stack.split('\n');
			traceArr.shift();
		}
		console.log(e);
		var trace = traceArr.join('\n');
		trace = trace.replace(/</g, '&lt;');
		trace = trace.replace(/>/g, '&gt;');
		VikDebug.print(trace, 'stack-trace');
	}
}

/**
 * dump mixed variable
 * @param mixed obj - variable for dump
 * @param object|string options - options.
 * 		if string, lower case means true, upper case means false
 * 		{depth: int}         "d=int"   max depth to dump obj     (default 5)
 * 		{append: true|false} "a|A"     append output, or replace (default false)
 * 		{types: true|false}  "t|T"     print var types           (default true)
 *		{steps: true|false}  "s|S"     open subobjects by steps  (default true)
 * 		{expand: 'a,b,c'}    "e=a,b,c" expand specific objects   (default '')
 * 			allowed items: *, window, document, tags, dom, jquery
 * 		{collapse: 'a,b,c/level'}  "E=a,b,c/level" collapse specific objects   (default '')
 * 			allowed items: *, function, array
 * 			level (optional) - depth, from witch collapsing starts ( begins from 1 )
 * 		{output: 'trace|console|return|returnDOM'} "o=[t|c|r|rd]" - output type (default 'trace')
 * @return string|objectDOM dump of variable
 */
function var_dump(obj, o){
	
	var vd = var_dump;
	
	o = (function(o){
		optObj = {
			depth: 3,	     //d=3
			append: false,   // a|A
			types: true,     // t|T
			highlight: true, // h|H
			steps: true,     // s|S
			expand: [],      // e='a,b,c'
			collapse: [],    // E='a,b,c'
			output: 'trace', // o=[t|c|r|rd]
			consoleTab: 'var_dump',
			_lvl: 0,
			_parent: null
		};
		if(o === 1 || o === true){
			optObj['output'] = 'return';
			return optObj;
		}
		if(typeof o == 'object'){
			for(var i in o)
				optObj[i] = o[i];
			
			o['expand'] = o['expand']?typeof(o['expand'])=='string'?o['expand'].split(','):o['expand']:[];
			for(var i = 0, l = o['expand'].length; i < l; i++)
				o['expand'][o['expand'][i].toLowerCase()] = 1;
			return optObj;
		}
		if(typeof o == 'string' && o.length){
			var arr = o.split(/\s+/);
			var pair;
			for(var i = 0; i < arr.length; i++){
				pair = arr[i].split(/\s*=\s*/);
				switch(pair[0]){
					case 'd': optObj.depth = parseInt(pair[1]) || 5; break;
					case 'a': optObj.append = true; break;
					case 'A': optObj.append = false; break;
					case 't': optObj.types = true; break;
					case 'T': optObj.types = false; break;
					case 'h': optObj.highlight = true; break;
					case 'H': optObj.highlight = false; break;
					case 's': optObj.steps = true; break;
					case 'S': optObj.steps = false; break;
					case 'e':
						var items = (pair[1] || '').replace(/\*/g, 'all').split(',');
						for(var j in items)
							optObj.expand[items[j]] = 1;
						break;
					case 'E':
						var items = (pair[1] || '').replace(/\*/g, 'all').split(',');
						for(var j in items){
							subparams = items[j].split('/');
							optObj.collapse[subparams[0]] = subparams[1] || 1;
						}
						break;
					case 'o':
						var subpair = (pair[1] || '').split('/');
						optObj.output = {t: 'trace', c: 'console', r: 'return', rd: 'returnDOM', 'console': 'console', 'return': 'return'}[subpair[0]];
						if(subpair[1])
							optObj.consoleTab = subpair[1];
						break;
				}
			}
		}
		return optObj;
	})(o);
	
	vd.getOpts = function(){
		return o;
	}
	vd.extend = vd.extend || function(){
		var output = {};
		for(var i in arguments)
			for(var j in arguments[i])
				output[j] = arguments[i][j];
		return output;
	};
	vd.node = vd.node || function(tag, attrs, text){
		var node = document.createElement(tag);
		var k;
		for(var i in attrs || {}){
			if(i=='style')
				for(var j in attrs[i])
					node.style[j] = attrs[i][j];
			else
				node[i] = attrs[i];
		}
		node.appendChild(document.createTextNode(text || ''));
		return node;
	};
	vd.textNode = vd.textNode || function(text){
		return document.createTextNode(text);
	};
	vd.strType = vd.strType || function(type){
		return vd.getOpts().types
			? vd.node('span', {style: {color: '#C44', fontStyle: 'italic', fontSize: '10px'}}, type + ' ')
			: vd.node('span');
	};
	vd.simpleRow = vd.simpleRow || function(type, text){
		var n = vd.node('span');
		n.appendChild(vd.strType(type));
		n.appendChild(vd.textNode(text));
		return n;
	}
	vd.tab = vd.tab || function(lvl){
		for(var i = 0, t = ''; i < lvl; i++)
			t += '|   ';
		return vd.node('span', {style: {color: '#DDD'}}, t);
	};
	vd.isArray = vd.isArray || function(obj){
		return Object.prototype.toString.call(obj) == '[object Array]';
	};
	vd.htmlspecialchars = vd.htmlspecialchars || function (str){
		str = str.replace(/</gi, '&lt;');
		str = str.replace(/>/gi, '&gt;');
		return str;
	};
	vd.detectSpecObj = vd.detectSpecObj || function (obj, expand, specObj){
		var specObj = {type: null, text: 0};
		if(obj === null){
			specObj.type = 'null';
			specObj.text = 'null';
		}else if(obj === window){
			specObj.type = 'DOM window';
			specObj.text = expand.all || expand.dom || expand.window ? null : '( window )';
		}else if(obj === document){
			specObj.type = 'DOM document';
			specObj.text = expand.all || expand.dom || expand.document ? null : '( document )';
		}else if(obj.hasOwnProperty('nodeName') && obj.hasOwnProperty('innerHTML')){
			specObj.type = 'DOM tag';
			specObj.text = expand.all || expand.dom || expand.tags ? null : obj.toString();
		}else if(obj.jquery){
			specObj.type = 'jquery';
			specObj.text = expand.all || expand.jquery ? null : '$(' + (obj.selector ? '"'+obj.selector+'"' : obj[0] ? '"' + obj[0].toString() + '"' : 'null') + ') [length: ' + obj.length + ']';
		}
		return specObj;
	}
	
	var parentNode = o._parent ? o._parent.parent : vd.node('div');
	var node = o._parent ? o._parent.node : vd.node('div');
	parentNode.appendChild(node);
	
	switch(typeof(obj)){
		case 'string': node.appendChild(vd.simpleRow('string', '"' + obj + '"')); break;
		case 'number': node.appendChild(vd.simpleRow('number', obj || '0')); break;
		case 'boolean': node.appendChild(vd.simpleRow('bool', obj ? 'true' : 'false')); break;
		case 'undefined': node.appendChild(vd.simpleRow('undefined', 'undefined')); break;
		case 'function': node.appendChild(vd.simpleRow('function', (o.collapse['function'] || o.collapse['all']) ? 'function()' : obj.toString())); break;
		case 'object':
			var specObj = vd.detectSpecObj(obj, o['expand']);
			var isArr = vd.isArray(obj);
			if(specObj.text){
				node.appendChild(vd.simpleRow(specObj.type, specObj.text));
			}else if(isArr && ( o.collapse['all'] || (o.collapse['array'] && o._lvl + 1 >= o.collapse['array']) ) ){
				node.appendChild(vd.simpleRow('array', '[ ' + obj.join(', ') + ' ]'));
			}else{
				var type = isArr ? 'array' : specObj.type ? specObj.type : 'object';
				var brackets = isArr ? ['[', ']'] : ['{', '}'];
				if(o._lvl >= o.depth){
					var ph = vd.node('span');
					ph.appendChild(vd.strType(type));
					ph.appendChild(vd.node('span', {style: {color: '#2998ED', cursor: 'pointer', fontWeight: 'bold'}, title: 'click to expand', onclick: function(){
						node.removeChild(ph);
						var_dump(obj, vd.extend(o, {depth: (o.depth + 2), _lvl: (o._lvl + 1), _parent: {node: node, parent: parentNode}}));}
					}, '{..}'));
					node.appendChild(ph);
				}else{
					node.appendChild(vd.strType(type));
					node.appendChild(vd.textNode(brackets[0]));
					var subs = [], sub, subsub;
					var subopts = {style: {'borderLeft': 'solid 1px #DDD', 'marginLeft': '16px'}};
					if(o.highlight){
						subopts.onmouseover = function(){this.style.backgroundColor = '#DBE6F2'; this.style.borderLeftColor = '#E57777';}
						subopts.onmouseout = function(){this.style.backgroundColor = 'transparent'; this.style.borderLeftColor = '#DDD';}
					}
					for(var i in obj){
						sub = vd.node('div', subopts);
						subsub = vd.node('div');
						// subsub.appendChild(vd.tab(o._lvl + 1));
						subsub.appendChild(vd.textNode(i + ' => '));
						try{
							if(obj[i] === obj)
								subsub.appendChild(vd.node('span', {style: {color: '#B99'}}, '*RECURSION*'));
							else
								vd(obj[i], vd.extend(o, {_lvl: (o._lvl + 1), _parent: {node: subsub, parent: sub}}));
						}catch(e){subsub.appendChild(vd.node('span', {style: {color: '#F55'}}, '*ERROR [' + e + ']*'));}
						subs.push(sub);
					}
					if(subs.length){
						for(var i = 0; i < subs.length; i++)
							parentNode.appendChild(subs[i]);
						var bottom = vd.node('div');
						// bottom.appendChild(vd.tab(o._lvl));
						bottom.appendChild(vd.textNode(brackets[1]));
						parentNode.appendChild(bottom);
					}else{
						node.appendChild(vd.textNode(brackets[1]));
					}
				}
			}
			break;
		default:
			node.appendChild(vd.strType(typeof(obj)));
			if(obj.toString){
				try{
					node.appendChild(vd.node('span', {}, obj.toString()));
				}catch(e){node.appendChild(vd.node('span', {style: {color: '#F55'}}, '*ERROR [' + e + ']*'));}
			}
	}
	
	// если узел вложенный, ничего возвращать не надо
	if(o._parent)
		return;
		
	switch(o.output){
		case 'returnDOM': return parentNode; break;
		case 'return': return parentNode.innerHTML; break;
		case 'console': VikDebug.print(parentNode, optObj.consoleTab, (o['append'] ? null : 1)); break;
		default: trace(parentNode, (o['append'] ? null : 1));
		// default: trace(parentNode);
	}
}

var VikDebug = {
	
	// включен ли VikDebug
	'isEnabled': true,
	
	settings: {
		// действие, выполняемое при вызове метода print
		// 'open' - открыть консоль
		// 'notify' - уведомить всплывающим сообщением
		// 'none' - ничего не делать
		'onPrintAction': 'notify',
		// отчищать ли предыдущее содержимое вкладки
		'clear': false,
		// расположение нового сообщения [top|bottom]
		'position': 'bottom',
		// активировать таб при вызове метода print
		'activateTab': true,
		// прокручивать ли вкладку до нового сообщения
		'scrollToNew': true
	},
	
	_isInited: false,
	_isOpened: false,
	_html: null,
	_tabs: {},
	_activeTabName: '',
	_normalScreenHeight: 300,
	_isFullScreen: false,
	_isBodyFixed: false,
	
	init: function(){
		
		if(this._isInited)
			return;
		
		this._createHtml();
		$('head').append('<link rel="stylesheet" href="http://scripts.vik-off.net/vik-debug.css" type="text/css" />');
		this._getHtml('wrapper').height(this._normalScreenHeight);
		if(this.isEnabled)
			this._bindHotkeys();
		this._isInited = true;
	},
	
	print: function(text, tabname, settings){
		
		if(!this._isInited){
			this.init();
			// alert('VikDebug is not inited. Use VikDebug.init(); on your document dom ready.');
			// return;
		}
		
		// замена третьего параметра на settings.clear = true
		if(settings === 1 || settings === true){
			settings = {};
			settings.clear = true;
		}
		
		// слияние настроек с дефолтными
		var s = {};
		for(var i in this.settings)
			s[i] = this.settings[i];
		for(var i in settings)
			s[i] = settings[i];
		
		tabname = tabname || 'default';
		
		// активация вкладки
		if(s.activateTab)
			this._activateTab(tabname);
		
		var tab = this._getTab(tabname);
		tab.msgIndex = s.clear ? 0 : tab.msgIndex + 1;
		var body = tab.body;
		var messageHtml = $('<div class="vik-debug-body-item"></div>')
			.append($('<div class="vik-debug-body-item-options"></div>')
				.append($(' <a href="#">close</a> ').click(function(){$(this).parent().parent().remove();return false;}))
				.append(' <span>#' + tab.msgIndex + '</span> '))
			.append($('<div />').append(text));
		
		// замер высоты вкладки
		var bodyHeight = body.height();
		
		// вставка сообщения во вкладку
		if(s.clear){
			body.html(messageHtml);
		}else{
			if(s.position == 'top')
				body.prepend(messageHtml);
			else
				body.append(messageHtml);
		}
		
		if(!this.isEnabled)
			return;
		
		// открытие консоли, или показ нотифая
		if(!this._isOpened){
			
			switch(s.onPrintAction){
				case 'open':
					this.open();
					break;
				case 'notify':
					this.notify('new debug message in <b>' + tabname + '</b> tab.');
					break;
			}
		}else{
		
			// прокрутка до нового сообщения
			if(s.activateTab && s.scrollToNew && s.position == 'bottom'){
				VikDebug._getHtml('wrapper').scrollTop(bodyHeight);
			}
		}
		
	},
	
	open: function(callback){
		
		if(!VikDebug.isEnabled)
			return;
		
		VikDebug._isOpened = true;
		VikDebug._getHtml('notifier').slideUp();
		VikDebug._getHtml('box').stop(true, true).slideDown('fast', callback);
	},
	
	close: function(){
		
		if(!VikDebug.isEnabled)
			return;
		
		VikDebug._isOpened = false;
		VikDebug._unfixBody();
		VikDebug._getHtml('box').stop(true, true).slideUp();
	},
	
	toggle: function(){
		
		if(!VikDebug.isEnabled)
			return;
		
		if(VikDebug._isOpened)
			VikDebug.close();
		else
			VikDebug.open();
	},
	
	notify: function(text){
		
		if(!VikDebug.isEnabled)
			return;
		
		VikDebug._getHtml('notifier').append('<div>' + text + '</div>').slideDown();
	},
	
	fullScreenToggle: function(){
		
		if(!VikDebug.isEnabled)
			return;
		
		if(VikDebug._isFullScreen){
			VikDebug._getHtml('wrapper').height(VikDebug._normalScreenHeight);
			VikDebug._getHtml('iconFullScreen').html('max');
			VikDebug._isFullScreen = false;
			VikDebug._unfixBody();
		}else{
			VikDebug._getHtml('wrapper').height(VikDebug._getFullScreenHeight());
			VikDebug._getHtml('iconFullScreen').html('norm');
			VikDebug._isFullScreen = true;
			VikDebug._fixBody();
		}
	},
	
	clearTab: function(){
		var t = this._tabs[this._activeTabName];
		if(!confirm('Очистить вкладку?')) return;
		if(!t) return;
		
		t.body.empty();
		t.msgIndex = 0;
	},
	
	_bindHotkeys: function(){
		$(document).keydown(function(e){
			if(e.keyCode == 192 && e.ctrlKey) // ctrl + ~
				VikDebug.toggle();
		});
	}, 
	_getFullScreenHeight: function(){
	
		return $(window).height() - 53;
	},
	
	_getTab: function(name){
		
		if(!this._tabs[name]){
			this._tabs[name] = {
				body: $('<div class="vik-debug-body" style="display: none;"></div>')
					.appendTo(this._getHtml('wrapper')),
				button: $('<a href="#" class="vik-debug-tab">' + name + '</a>')
					.click(function(){VikDebug._activateTab(name);return false;})
					.appendTo(this._getHtml('tabBox')),
				msgIndex: 0
			}
		}
		return this._tabs[name];
	},
	
	_activateTab: function(tabname){
		
		// скрыть предыдущий таб
		if(this._activeTabName){
			var oldTab = this._getTab(this._activeTabName);
			oldTab.body.hide();
			oldTab.button.removeClass('active');
		}
		
		var newTab = this._getTab(tabname);
		newTab.body.show();
		newTab.button.addClass('active');
		this._activeTabName = tabname;
	},
	
	_getHtml: function(name){
		
		return this._html[name];
	},
	
	_createHtml: function(){
		
		this._html = {
			'box': $('<div id="vik-debug-box" style="display: none;"></div>'),
			'notifier': $('<div id="vik-debug-notifier" style="display: none;"></div>')
				.click(function(){$(this).slideUp().empty();VikDebug.open();})
				.mouseleave(function(){var t = $(this);t.stop(true, true).delay(1000).slideUp(1000, function(){t.empty()});}),
			'head': $('<div id="vik-debug-head"></div>'),
			'title': $('<div id="vik-debug-title">Отладочная консоль</div>'),
			'preWrapper': $('<div id="vik-debug-pre-wrapper"></div>'),
			'wrapper': $('<div id="vik-debug-wrapper"></div>'),
			'tabBox': $('<div id="vik-debug-tab-box"></div>'),
			
			'iconClose': $('<a class="vik-debug-icon" href="#">x</a>')
				.click(function(){VikDebug.close();return false;}),
			'iconClearTab': $('<a class="vik-debug-icon" href="#">clear tab</a>')
				.click(function(){VikDebug.clearTab();return false;}),
			'iconFullScreen': $('<a class="vik-debug-icon" href="#">max</a>')
				.click(function(){VikDebug.fullScreenToggle();return false;})
		};
		this._html.notifier.appendTo('body');
		this._html.box
			.append(this._html.head
				.append(this._html.iconClose)
				.append(this._html.iconClearTab)
				.append(this._html.iconFullScreen)
				.append(this._html.title))
			.append(this._html.preWrapper.append(this._html.wrapper))
			.append(this._html.tabBox)
			.appendTo('body');
		
	},
	
	_fixBody: function(){
		
		if(this._isBodyFixed)
			return;
			
		document.body.style.height = '100%';
		document.body.style.overflow = 'hidden';
		this._isBodyFixed = true;
	},
	
	_unfixBody: function(){
		
		if(!this._isBodyFixed)
			return;
			
		document.body.style.height = 'auto';
		document.body.style.overflow = 'auto';
		this._isBodyFixed = false;
	}
};


