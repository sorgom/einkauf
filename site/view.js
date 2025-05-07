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
        // console.log('url', params.join(','));
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
        const url = this.url(trg, ...params);
        console.log('send', url)
        xhr.open('GET', url, true);
        xhr.send(null);
        const t2 = performance.now();
    }
}

class Menu extends Usr
{
    constructor(uid, entries)
    {
        super(uid);
        const _this = this;
        let done = [];
        let post = [];
        const dl = div();
        dl.className = 'listing';
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
            else dl.appendChild(a);
        }
        for (const a of post) dl.appendChild(a);
        for (const a of done) dl.appendChild(a);
        const d = div();
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
        return this.has('y') ? 'y' : this.has('x') ? 'x' : '';
    }
    x()
    {
        if (this.cll.contains('y')) this.clear();
        else this.cll.toggle('x');
    }
    y()
    {
        if (this.cll.contains('x')) this.set('y');
        else this.cll.toggle('y');
    }
    has(cl)
    {
        return this.cll.contains(cl);
    }
}

class Item
{
    ctrl;
    inr;
    tgl;
    constructor(ctrl, inr, data, par)
    {
        const _this = this;
        this.ctrl = ctrl;
        this.inr = inr;
        const [ttl, cl ] = data;
        const di = div(par);
        di.className = 'item';
        this.tgl = new Toggle(di, cl);
        const a1 = tLink(ttl, di);
        a1.className = 'a1';
        a1.onclick = function()
        {
            _this.tgl.x();
            _this.note();
        }
        const a2 = anc(di);
        a2.className = 'a2';
        a2.onclick = function()
        {
            _this.tgl.y();
            _this.note();
        }
    }
    toggle()
    {
        return this.tgl;
    }
    note()
    {
        this.ctrl.note(this.inr, this.tgl.cl());
    }

}

class Items extends Usr
{
    cnr;
    top;
    items = [];
    conf;
    constructor(uid, data)
    {
        super(uid);
        const [ cnr, hl, cl, entries ] = data;
        const _this = this;
        this.cnr = cnr;


        const dl = div();
        dl.className = 'listing';

        const a = tLink(hl, dl);
        a.classList.add('items', 'top');
        a.onclick = function() { _this.view(); }
        this.top = new Toggle(a, cl);

        let inr = 0;
        for (const e of entries)
        {
            if (Array.isArray(e))
            {
                this.items.push(new Item(this, inr, e, dl).toggle());
                ++inr;
            }
            else if (e)
            {
                const h = make('h2', dl);
                h.innerText = e;
            }
            else make('hr', dl);
        }
        const d = div();
        d.className = 'mn bottom';
        const a1 = iLink('remove', d);
        a1.onclick = function() { _this.conf_remove.show(); }
        const a2 = iLink('reset', d);
        a2.onclick = function() { _this.conf_reset.show(); }
        const a3 = iLink('up', d);
        a3.onclick = function() { _this.view(); }

        this.conf_remove = new Confirm('remove', function () { _this.go('remove', cnr); });
        this.conf_reset  = new Confirm('reset', function () { _this.reset(); });
    }

    note(inr, cl)
    {
        console.log('note', cl, this.cnr, inr);
        this.send('_state', cl, this.cnr, inr);
        const clo = this.top.cl();
        this.top.clear();
        let cln = ''
        if (cl)
        {
            let cnt = { 'x':0, 'y':0};
            for (const i of this.items)
            {
                ++cnt[i.cl()];
            }
            const cx = cnt['x'];
            const cy = cnt['y'];
            console.log('log:', cx, cy);
            cln = cx + cy < this.items.length ? '' : cy > 0 ? 'y' : 'x';
            this.top.set(cln);
        }
        if (cln != clo)
        {
            console.log('cln:', cln);
            this.send('_state', cln, this.cnr);
        }
    }

    reset()
    {
        this.top.clear();
        for (const i of this.items) i.clear();
        this.send('_reset', this.cnr);
    }
}

class Confirm
{
    constructor(cl, func)
    {
        const _this = this;
        const dc = div();
        dc.className = 'confirm';
        dc.onclick = function () { _this.hide(); }
        const di = div(dc);
        const a = iLink(cl, di);
        a.classList.add('confirm');
        a.onclick = func;
        this.cll = dc.classList;
    }
    hide()
    {
        this.cll.remove('v');
    }
    show()
    {
        this.cll.add('v');
    }
}

class InputForm extends Usr
{
    constructor(uid, txt)
    {
        super(uid);
        const _this = this;
        const frm = document.createElement('form');
        frm.action = 'save.php';
        frm.method = 'post';

        const te = textarea(frm, 50, 'txt', txt);
        te.autofocus = true;

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
