$(function(){
$.fn.banner = function(o) {
	o = $.extend({duration: 5000, auto:true, len:0, receivers:[]}, o || {});
			
	return this.each(function(){
		var me = $(this),
			duration = o.duration,
			auto = o.auto
			len = me.find(o.len).length,
			invoker = new Invoker(len, duration, auto);
			
		for (var i = 0; i < o.receivers.length; i++) {
			var html, req, command = null;
				
			for (var key in o.receivers[i]) {
				var rec = o.receivers[i][key];
				if (key.toString() === "html") {
					html = rec;
					req.getHtml(me.find(html));
				}
				if (key.toString() === "receive") {
					req = new rec();
				}
				if (key.toString() === "event") {
					for (var event in rec) {
						me.find(html).bind(event.toString(), invoker[rec[event]]);
					}
				}
				if (key.toString() === "param") {
					req.getParam(rec);
				}
			}
			command = new Command(req);
			invoker.add(command);
		}
		invoker.init();	
	});
};
}); 

function Invoker(len, duration, auto) {
	this.commandList = [];
	this.curNum = 0,
	this.len = len,
	this.duration = duration
	this.auto = auto;
	var timer = null
	var _this = this;
	this.loop = function() {
		timer = this.auto ? window.setInterval(function(){_this.curNum++; _this.execute()}, this.duration) : null;
	}
	this.pause = function() {
		clearInterval(timer);	
	}
	this.preview = function() {
		_this.pause();
		_this.curNum--;
		_this.execute();
		_this.loop();
	}
	this.next = function() {
		_this.pause();
		_this.curNum++;
		_this.execute();
		_this.loop();
	}
	this.play = function() {
		_this.loop();
	}
	this.stop = function() {
		_this.pause();
	}
	this.choose = function(event) {
		_this.pause();
		_this.curNum = $(event.target).parent().find(event.target.nodeName).index(event.target);
		_this.execute();
		_this.loop();
	}
}
Invoker.prototype.add = function(command){
	this.commandList.push(command);
};
Invoker.prototype.init = function() {
	for (var i = 0; i < len - 1; i++) {
		this.commandList[i].init();
	}
	this.loop();
};
Invoker.prototype.execute = function() {
	if (this.curNum === this.len) {
	  this.curNum = 0;	
	}
	if (this.curNum === -1) {
	  this.curNum = this.len - 1;	
	}
	for (var i = 0; i < this.commandList.length; i++) {
	  this.commandList[i].execute(this.curNum);
	}
};
function Command(req) {
	this.req = req;
} 
Command.prototype.init = function() { 
	this.req.ready();
};
Command.prototype.execute = function(num) {
	this.req.active(num);
};

function BaseRec() {
	this.html = null;
	this.param = {};
}
BaseRec.prototype.getHtml = function(html) {
	this.html = html;
};
BaseRec.prototype.getParam = function(param) {
	this.param = param;
};
BaseRec.prototype.ready = function() {};
BaseRec.prototype.active = function(num) {};

function DefaultRec() {
	BaseRec.call(this);
}
DefaultRec.prototype = new BaseRec();
DefaultRec.prototype.constructor = DefaultRec;
DefaultRec.prototype.ready = function() {
	this.html.eq(0).addClass("active");	
};
DefaultRec.prototype.active = function(num) {
	this.html.removeClass("active");
	this.html.eq(num).addClass("active");
};

function FadeRec() {
	
}
FadeRec.prototype = new BaseRec();
FadeRec.prototype.constructor = FadeRec;
FadeRec.prototype.ready = function() {
	this.html.hide();
	this.html.eq(0).show();
};
FadeRec.prototype.active = function(num) {
	this.html.fadeOut(this.param.speed);
	this.html.eq(num).fadeIn(this.param.speed);
};

function LeftRec() {
	this.w = 0;
}
LeftRec.prototype = new BaseRec();
LeftRec.prototype.constructor = LeftRec;
LeftRec.prototype.ready = function() {
	this.w = this.html.find("li:first").outerWidth();
	var l = this.html.find("li").size();
	this.html.width(this.w * l);
};
LeftRec.prototype.active = function(num) {
	this.html.animate({"left":-num * this.w}, this.param.speed);
};