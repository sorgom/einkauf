
var uid = undefined;

function p(par, ttl)
{
    let p = document.createElement('p');
    p.innerText = ttl;
    par.appendChild(p);
    return p;
}

function tLink(par, ttl)
{
    let a = anc(par);
    p(a, ttl);
    return a;
}

function iLink(par, cl)
{
    let a = anc(par);
    a.className = 'i ' + cl;
    return a;
}

function bLink(par)
{
    let a = iLink(par, 'back');
    a.onclick = function () { history.back(); }
    return a;
}


function Entry(sid, ttl)
{
    let d = div();
    d.sid = sid;
    let a1 = anc(d);
    a1.className = 'a1';
    this.p = p(a1, ttl);
    let a2 = anc(d);
    a2.className = 'a2';
    this.sid = sid;
    this.d = d;
    this.a1 = a1;
    this.a2 = a2;
}
Entry.prototype.add    = function(cl) { this.d.classList.add(cl); }
Entry.prototype.remove = function(cl) { this.d.classList.remove(cl); }
Entry.prototype.toggle = function(cl) { this.d.classList.toggle(cl); }
Entry.prototype.has    = function(cl) { return this.d.classList.contains(cl); }
Entry.prototype.put    = function(tg) { tg.appendChild(this.d); }
Entry.prototype.node   = function() { return this.d; }
Entry.prototype.ttl    = function() { return this.p.innerText; }
Entry.prototype.reset  = function(snd=false)
{
    const chg =  reset(this.d);
    if (snd && chg) sendObj('_reset.php', this.d);
    return chg;
}

//  bottom action menu
function mn_bottom()
{
    let bt = div();
    bt.id = 'bottom';
    bt.className = 'mn';
    document.body.appendChild(bt);
    return bt;
}

// remove chapter confirmation
function Conf()
{
    let dc = div(document.body);
    let _this = this;
    dc.id = 'conf';
    dc.onclick = function () { _this.hide(); }
    let di = div(dc);
    this.p = p(di);
    let dm = div(di);
    dm.className = 'mn';
    let a1 = anc(dm);
    a1.className = 'back';
    a1.onclick = function () { _this.hide(); }
    let a2 = anc(dm);
    a2.className = 'remove';
    a2.onclick = function () { _this.execute(); }
    this.dc = dc;
}
Conf.prototype.hide = function()
{
    this.dc.classList.remove('v');
}
Conf.prototype.show = function(cnr, ttl)
{
    this.sid = cnr;
    this.p.innerText = ttl;
    this.dc.classList.add('v');
}
Conf.prototype.execute = function()
{
    window.location.replace('remove.php?' + uid + '&' + this.sid);
}

//  bottom action menu
function mn_top()
{
    let bt = div();
    bt.id = 'top';
    bt.className = 'mn';
    document.body.appendChild(bt);
    return bt;
}

function insertAfter(trg, obj)
{
    trg.parentNode.insertBefore(obj, trg.nextSibling);
}

function div(par=undefined)
{
    let d = document.createElement('div');
    if (par) par.appendChild(d);
    return d;
}
function anc(par)
{
    let a = document.createElement('a');
    par.appendChild(a);
    return a;
}

function reset(obj)
{
    const clo = obj.className;
    obj.classList.remove('x');
    obj.classList.remove('y');
    return obj.className != clo;
}

//  send object state (class)
function send(trg, sid, cl='')
{
    var data = 'uid=' + uid + '&id=' + sid + '&st=' + cl;
    console.log('send', trg, data);
    var xhr = new XMLHttpRequest();
    xhr.open('POST', trg, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(data);
}

function sendObj(trg, obj)
{
    const cl = obj.classList.contains('y') ? 'y' : obj.classList.contains('x') ? 'x' : '';
    send(trg, obj.sid, cl);
}

removeConf = new Conf();
// removeConf.show(22, 'wumpel');
