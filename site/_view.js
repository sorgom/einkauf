
var uid = undefined;

function p(par, ttl)
{
    let p = document.createElement('p');
    p.innerText = ttl;
    par.appendChild(p);
    return p;
}

function tlink(par, ttl)
{
    let a = anc(par);
    p(a, ttl);
    return a;
}

function ilink(par, cl)
{
    let a = anc(par);
    a.className = 'i ' + cl;
    return a;
}

function Entry(sid, ttl)
{
    let d = div();
    d.sid = sid;
    let a1 = anc(d);
    a1.className = 'a1';
    p(a1, ttl);
    let a2 = anc(d);
    a2.className = 'a2';
    this.d = d;
    this.a1 = a1;
    this.a2 = a2;
}
Entry.prototype.add    = function(cl) { this.d.classList.add(cl); }
Entry.prototype.remove = function(cl) { this.d.classList.remove(cl); }
Entry.prototype.toggle = function(cl) { this.d.classList.toggle(cl); }
Entry.prototype.has    = function(cl) { return this.d.classList.contains(cl); }
Entry.prototype.put    = function(tg) { tg.appendChild(this.d); }

//  bottom action menu
function mn_bottom()
{
    let bt = div();
    bt.id = 'bottom';
    bt.className = 'mn';
    document.body.appendChild(bt);
    return bt;
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
    obj.classList.remove('x');
    obj.classList.remove('y');
}

//  send object state (class)
function send(trg, obj)
{
    const cl = obj.classList.contains('y') ? 'y' : obj.classList.contains('x') ? 'x' : '';
    var data = 'uid=' + uid + '&id=' + obj.sid + '&st=' + cl;
    console.log('send', trg, data);
    var xhr = new XMLHttpRequest();
    xhr.open('POST', trg, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(data);
}
