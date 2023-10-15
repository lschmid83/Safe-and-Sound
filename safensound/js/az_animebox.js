xAddEventListener(window, 'load', btnInit, false);
xAddEventListener(window, 'load', trgInit, false);
xAddEventListener(window, 'resize', animBoxesResize, false);

function btnInit()
{
	var i, a = xGetElementsByClassName('jsButton');
	for (i = 0; i < a.length; ++i) { a[i].onclick = btnOnClick; }
}

function trgInit()
{
	var i, a = xGetElementsByClassName('jsTrigger');
	for (i = 0; i < a.length; ++i) { a[i].onmouseover = trgOnOver; a[i].onmouseout = trgOnOut; }
	a = xGetElementsByClassName('jsContainer');
	for (i = 0; i < a.length; ++i) { a[i].onmouseover = trgOnOver; a[i].onmouseout = trgOnOut; }
}

function btnOnClick()
{
	var i, xa = animBox.instances;
	for (i = 0; i < xa.length; ++i)
	{
		if ('btn_' + xa[i].eid == this.id) {
			if (xa[i].st == 1) { xa[i].expand(); }
			else if (xa[i].st == 2) { xa[i].collapse(); }
			else xa[i].animate();
		}
	}
}

function trgOnOver()
{
	var i, xa = animBox.instances;
	for (i = 0; i < xa.length; ++i)
	{
		if ('btn_' + xa[i].eid == this.id || xa[i].eid == this.id) {
			if (xa[i].out_tmr) { clearTimeout(xa[i].out_tmr); xa[i].out_tmr = null; }
			if (xa[i].st == 1) { xa[i].expand(); xa[i].act = 1; }
			else { xa[i].act = 1; }
		}
	}
}

function trgOnOut()
{
	var i, xa = animBox.instances;
	for (i = 0; i < xa.length; ++i)
	{
		if ('btn_' + xa[i].eid == this.id || xa[i].eid == this.id) {
			xa[i].act = 0;
			if (!xa[i].out_tmr) xa[i].out_tmr = setTimeout('trgOnTimer(' + xa[i].idx + ')', 400);
		}
	}
}

function trgOnTimer(idx)
{
	var xa = animBox.instances;
	xa[idx].out_tmr = null;
	if (xa[idx].act == 0) { xa[idx].collapse(); }
}

function animBoxesResize()
{
	var i, a = animBox.instances;
	for (i = 0; i < a.length; ++i)
	{
		if (a[i].pe) { a[i].reposition(); }
	}
}

function animBox(e, st, at, qc, tt, orf, otf, oed, oea, oef) // Object Prototype
{
	this.init(e, st, at, qc, tt, orf, otf, oed, oea, oef);
	var a = animBox.instances;
	var i;
	for (i = 0; i < a.length; ++i) { if (!a[i]) break; } // find an empty slot
	a[i] = this;
	this.idx = i;
	return this;
}

animBox.instances = []; // static property

animBox.prototype.init = function(e, st, at, qc, tt, orf, otf, oed, oea, oef)
{
	this.e = xGetElementById(e); // e is object ref or id str
	this.pe = 'bababah'; // pe is button or triger object ref or id str
	this.xs = null; // relative x shift: '-', null, '+'
	this.ys = null; // relative y shift: '-', null, '+'
	this.eid = e; // e is object ref or id str
	this.act = 0; // mouse status: 0=out, 1=over
	this.st = st || 2; // status: 1=collapsed, 2=expanded
	this.at = at || 2; // acceleration type: 1=linear, 2=sine, 3=cosine
	this.qc = qc || 1; // acceleration quarter-cycles
	this.tt = tt || 2000; // total time
	this.orf = orf; // onRun function
	this.otf = otf; // onTarget function
	this.oed = oed; // delay before calling onEnd
	this.oea = oea; // onEnd argument
	this.oef = oef; // onEnd function
	this.to = 20; // timer timeout
	this.as = false; // auto-start

	this.x = xPageX(this.e);
	this.y = xPageY(this.e);
	this.w = xWidth(this.e);
	this.h = xHeight(this.e);
	if (st == 1) // collapsed
	{
		xHide(this.e);
		xResizeTo(e, this.w, 2); // setting (…,1) seemed to cause problems? (perhaps because this ele has a border)
	}
	return this;
};

animBox.prototype.reposition = function(e, xs, ys)
{
	var x, y, a = animBox.instances[this.idx];
	if (!e)
	{
		e = a.pe;
		xs = a.xs;
		ys = a.ys
	}
	if (!xGetElementById(e)) return;
	var px = xPageX(e);
	var py = xPageY(e);
	var pw = xWidth(e);
	var ph = xHeight(e);
	a.pe = e;
	a.xs = xs;
	a.ys = ys;
	if (xs == '+') { x = px + pw; }
	else if (xs == '-') { x = px - a.w + pw; }
	else { x = px; }
	if (ys == '+') { y = py + ph; }
	else if (ys == '-') { y = py - a.h; }
	else { y = py; }
	xMoveTo(a.eid, x, y);
}

animBox.prototype.animate = function()
{
	var a = animBox.instances[this.idx];
	if (a.st == 1) { alert('expanding'); a.expand(); }
	else if (a.st == 2) { alert('collapsing'); a.collapse(); }
	else return;
}

animBox.prototype.expand = function()
{
	var a = animBox.instances[this.idx];
	a.x1 = xWidth(a.e); // start size
	a.y1 = xHeight(a.e);
	a.x2 = a.w; // end size
	a.y2 = a.h;
	a.orf = onRun;
	a.otf = onTarget;
	a.oea = 0;
	a.oef = onEnd;

	xShow(a.e);
	a.start();

	function onRun(o) { xResizeTo(o.e, Math.round(o.x), Math.round(o.y)); }
	function onTarget(o) { }
	function onEnd(o) { xResizeTo(o.e, o.w, o.h); o.st = 2; }
}

animBox.prototype.collapse = function()
{
	var a = animBox.instances[this.idx];
	a.x1 = xWidth(a.e); // start size
	a.y1 = xHeight(a.e);
	a.x2 = a.w; // end size
	a.y2 = 2;
	a.orf = onRun;
	a.otf = onTarget;
	a.oea = 0;
	a.oef = onEnd;

	a.start();
	return a;

	function onRun(o) { xResizeTo(o.e, Math.round(o.x), Math.round(o.y)); }
	function onTarget(o) { }
	function onEnd(o) { xHide(o.e); xResizeTo(o.e, o.w, 2); o.st = 1; }
}

animBox.prototype.start = function()
{
	var a = this;
	if (a.at == 1) { a.ap = 1 / a.tt; } // linear acceleration
	else { a.ap = a.qc * (Math.PI / (2 * a.tt)); } // sine and cosine acceleration
	// magnitudes
	if (xDef(a.x1)) { a.xm = a.x2 - a.x1; }
	if (xDef(a.y1)) { a.ym = a.y2 - a.y1; }
	// end point if even number of cyles
	if (!(a.qc % 2)) {
		if (xDef(a.x1)) a.x2 = a.x1;
		if (xDef(a.y1)) a.y2 = a.y1;
	}
	if (!a.tmr) { // if not already running
		var d = new Date();
		a.t1 = d.getTime(); // start time
		a.tmr = setTimeout('animBox.run(' + a.idx + ')', 1);
	}
};

animBox.run = function(i) // static method
{
	var a = animBox.instances[i];
	if (!a) return;
	var d = new Date();
	a.et = d.getTime() - a.t1; // elapsed time
	if (a.et < a.tt)
	{
		a.tmr = setTimeout('animBox.run(' + i + ')', a.to);
		a.af = a.ap * a.et;  // linear acceleration
		if (a.at == 2) { a.af = Math.abs(Math.sin(a.af)); } // sine acceleration
		else if (a.at == 3) { a.af = 1 - Math.abs(Math.cos(a.af)); } // cosine acceleration
		// instantaneous point
		if (xDef(a.x1)) a.x = a.xm * a.af + a.x1;
		if (xDef(a.y1)) a.y = a.ym * a.af + a.y1;
		a.orf(a); // onRun
	}
	else
	{
		var rep = false; // repeat
		if (xDef(a.x2)) a.x = a.x2;
		if (xDef(a.y2)) a.y = a.y2;
		a.tmr = null;
		a.otf(a); // onTarget
		if (xDef(a.oef)) { // onEnd
			if (a.oed) setTimeout(a.oef, a.oed); // no args passed to oef if oed or oef is str
			else if (xStr(a.oef)) { rep = eval(a.oef); }
			else { rep = a.oef(a, a.oea); }
		}
		if (rep) { a.resume(true); } // there may be a problem here if the anim is paused and oef returns true
  }
};

animBox.prototype.kill = function()
{
	animBox.instances[this.idx] = null;
};
