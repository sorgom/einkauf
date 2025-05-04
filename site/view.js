'use strict';


function make(what, par=document.body)
{
    let e = document.createElement(what);
    if (par) par.appendChild(e);
    return e;
}

function div(par=document.body)
{
    return make('div', par);
}

function p(ttl, par=document.body)
{
    let p = make('p', par);
    p.innerText = ttl;
    return p;
}

function anc(par)
{
    return make('a', par);
}

function tLink(ttl, par=document.body)
{
    let a = anc(par);
    a.className = 'p';
    p(ttl, a);
    return a;
}

function iLink(cl, par=document.body)
{
    let a = anc(par);
    a.className = 'i ' + cl;
    return a;
}

function bLink(par=document.body)
{
    let a = iLink('back', par);
    a.onclick = function () { history.back(); }
    return a;
}

function textarea(par, rows, name, val)
{
    let ta = document.createElement('textarea');
    ta.rows = rows;
    ta.name = name;
    ta.value = val;
    ta.className = name;
    par.appendChild(ta);
    return ta;
}

function hidden(par, name, val)
{
    let ip = document.createElement('input');
    ip.type = 'hidden';
    ip.name = name;
    ip.value = val;
    par.appendChild(ip);
    return ip;
}

function sClass(obj)
{
    const cll = obj.classList;
    return cll.contains('y') ? 'y' : cll.contains('x') ? 'x' : '';
}

function reset(e)
{
    e.classList.remove('x', 'y');
}

class Usr
{
    uid;
    sep = '-';

    constructor(uid)
    {
        this.uid = uid;
    }
    url(trg, ...params)
    {
        return trg + '.php?' + [ this.uid, ...params].join(this.sep);
    }
    go(trg, ...params)
    {
        window.location.replace(this.url(trg, ...params));
    }
    view(...params)
    {
        const url = '/?' + [ this.uid, ...params].join(this.sep);
        window.location.replace(url);
    }
    send(trg, ...params)
    {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', this.url(trg, ...params), true);
        xhr.send(null);
        const t2 = performance.now();
    }

    process() {}

    get(...params)
    {
        const _this = this;
        const t1 = performance.now();
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function()
        {
            if (xhr.readyState == 4 && xhr.status == 200)
            {
                const t2 = performance.now();
                console.log('DATA', Math.round(t2 - t1));
                _this.process(xhr.responseText);
                const t3 = performance.now();
                console.log('PROC', Math.round(t3 - t2));
            }
        }
        xhr.open('GET', this.url('_get', ...params), true);
        xhr.send(null);
    }
}


class Menu extends Usr
{
    constructor(uid)
    {
        super(uid);
        this.get('menu');
    }

    process(txt)
    {
        const data = JSON.parse(txt);
        const [ uid, entries ] = data;
        const _this = this;
        let done = [];
        let post = [];
        const bd = document.body;
        for (const [cnr, ttl, cl] of entries)
        {
            const a = tLink(ttl, undefined);
            a.onclick = function() { _this.view(cnr); }
            if (cl)
            {
                a.classList.add(cl);
                if      (cl == 'y') post.push(a);
                else if (cl == 'x') done.push(a);
            }
            else bd.appendChild(a);
        }
        for (const a of post) bd.appendChild(a);
        for (const a of done) bd.appendChild(a);
        const d = div(bd);
        d.className = 'mn bottom';
        const a = iLink('edit', d);
        a.onclick = function() { _this.view('e'); }
    }
}

class Toggle
{
    cll;
    constructor(elem, cl)
    {
        this.cll = elem.classList;
        this.set(cl);
    }
    set(cl)
    {
        this.clear();
        if (cl) this.cll.add(cl);
    }
    clear()
    {
        this.cll.remove('x', 'y');
    }
    cl()
    {
        return this.cll.contains('y') ? 'y' : this.cll.contains('x') ? 'x' : '';
    }
    x()
    {
        if (this.cll.contains('y')) this.clear();
        else this.cll.toggle('x');
    }
    y()
    {
        if (this.cll.contains('x')) this.set('y');
        else this.cll.toggle('x');
    }
}

class Item
{
    par;
    inr;
    di;
    cl = '';
    constructor(par, inr, data)
    {
        const _this = this;
        this.par = par;
        this.inr = inr;
        const [ttl, cl ] = data;
        const di = div();
        di.className = 'item';
        if (cl)
        {
            di.classList.add(cl);
            this.cl = cl;
        }
        const a1 = tLink(ttl, di);
        a1.className = 'a1';
        a1.onclick = function() { _this.x(); }
        const a2 = anc(di);
        a2.className = 'a2';
        a2.onclick = function() { _this.y(); }
        this.di = di;
    }
    icl()
    {
        return this.cl;
    }
    x()
    {
        if (this.di.classList.contains('y')) this.reset();
        else this.di.classList.toggle('x');
        this.cl = sClass(this.di);
        this.note();
    }
    y()
    {
        if (this.di.classList.contains('x')) this.reset();
        this.di.classList.toggle('y');
        this.cl = sClass(this.di);
        this.note();
    }
    note()
    {
        this.par.note(this.inr, this.cl);
    }
    reset()
    {
        reset(this.di);
        // this.di.classList.remove('x');
        // this.di.classList.remove('y');
        console.log('reset:', this.di.className);
    }
}

class Items extends Usr
{
    cnr;
    top;
    items = [];
    cl = '';
    conf;
    constructor(uid, cnr)
    {
        super(uid);
        this.get('items', cnr);
    }

    process(txt)
    {
        const [ uid, cnr, hl, cl, entries ] = JSON.parse(txt);
        const _this = this;
        this.cnr = cnr;
        const bd = document.body;
        const a = tLink(hl, bd);
        a.classList.add('items', 'top');
        if (cl) {
            a.classList.add(cl);
            this.cl = cl;
        }
        a.onclick = function() { _this.view(); }
        this.top = a;
        let inr = 0;
        for (const e of entries)
        {
            if (Array.isArray(e))
            {
                this.items.push(new Item(this, inr, e));
                ++inr;
            }
            else if (e)
            {
                const h = make('h2', bd);
                h.innerText = e;
            }
            else make('hr', bd);
        }
        const d = div();
        d.className = 'mn bottom';
        const a1 = iLink('remove', d);
        a1.onclick = function() { _this.conf.show(); }
        const a2 = iLink('reset', d);
        a2.onclick = function() { _this.reset(); }
        const a3 = iLink('up', d);
        a3.onclick = function() { _this.view(); }

        this.conf = new ConfirmRemove(uid, cnr, hl);

    }

    note(inr, cl)
    {
        this.send('_state', cl, this.cnr, inr);
        let cnt = { 'x':0, 'y':0};
        for (const i of this.items)
        {
            console.log(i.inr, i.icl());
            ++cnt[i.icl()];
        }
        const cx = cnt['x'];
        const cy = cnt['y'];
        console.log('log:', cx, cy);
        const cln = cx + cy < this.items.length ? '' : cy > 0 ? 'y' : 'x';
        const clo = this.cl;
        if (cln != clo)
        {
            if (clo) this.top.classList.remove(clo);
            if (cln) this.top.classList.add(cln);
            this.cl = cln;
            this.send('_state', cln, this.cnr);
        }
    }

    reset()
    {
        console.log('reset:', this.items.length);
        reset(this.top);
        for (const i of this.items) i.reset();
        this.send('_reset', this.cnr);
    }
}

// remove chapter confirmation
class ConfirmRemove extends Usr
{
    constructor(uid, cnr, ttl)
    {
        super(uid);
        const _this = this;
        const dc = div();
        dc.id = 'conf';
        dc.onclick = function () { _this.hide(); }
        const di = div(dc);
        const a1 = iLink('remove', di);
        a1.onclick = function () { _this.go('remove', cnr); }
        this.dc = dc;
    }
    hide()
    {
        this.dc.classList.remove('v');
    }
    show()
    {
        console.log('CONF show');
        this.dc.classList.add('v');
    }
}

class InputForm extends Usr
{
    constructor(uid)
    {
        super(uid);
        this.get('txt');
    }

    process(txt)
    {
        const _this = this;
        const frm = document.createElement('form');
        frm.action = 'save.php';
        frm.method = 'post';

        textarea(frm, 50, 'txt', txt);

        hidden(frm, 'uid', this.uid);
        document.body.appendChild(frm);

        const d = div();
        d.className = 'mn bottom';
        const a = iLink('back', d);
        a.onclick = function() { _this.view(); }
        const b = iLink('save', d);
        b.onclick = function() { frm.submit(); }
    }
}
