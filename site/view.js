'use strict';

class Elem
{
    elem;
    cll;
    constructor(what)
    {
        this.elem = document.createElement(what);
        this.cll = this.elem.classList;
        return this;
    }
    class(cl)
    {
        this.elem.className = cl;
        return this;
    }
    into(par)
    {
        par.elem.appendChild(this.elem);
        return this;
    }
    bd()
    {
        document.body.appendChild(this.elem);
        return this;
    }
    click(func)
    {
        this.elem.onclick = func;
        return this;
    }

    add(cl)
    {
        if (cl) this.cll.add(cl);
        return this;
    }
    remove(... cl)
    {
        this.cll.remove(... cl);
        return this;
    }
    toggle(cl)
    {
        this.cll.toggle(cl);
        return this;
    }
    has(cl)
    {
        return this.cll.contains(cl);
    }
}

class T_Elem extends Elem
{
    constructor(what, txt)
    {
        super(what);
        this.elem.innerText = txt;
        return this;
    }
}

class Link extends Elem
{
    constructor() { return super('a'); }
}

class P extends T_Elem
{
    constructor(txt) { return super('p', txt); }
}

class TxtLink extends Link
{
    constructor(txt)
    {
        super();
        new P(txt).into(this);
        return this;
    }
}

class ImgLink extends Link
{
    icl;
    constructor(cl)
    {
        super();
        this.icl = cl;
        return this.class('i ' + cl);
    }
    set(cl)
    {
        if (cl != this.icl)
        {
            if (this.icl) this.remove(this.icl);
            this.add(cl);
            this.icl = cl;
        }
        return this;
    }
}

class Div extends Elem
{
    constructor() { return super('div'); }
}

class HR extends Elem
{
    constructor() { return super('hr'); }

}
class H2 extends T_Elem
{
    constructor(txt) { return super('h2', txt); }
}

class Form extends Elem
{
    constructor(action, method='post')
    {
        super('form');
        this.elem.action = action;
        this.elem.method = method;
    }
    submit()
    {
        this.elem.submit();
    }
}

class TextArea extends Elem
{
    constructor(name, rows)
    {
        super('textarea');
        this.elem.name = name;
        this.elem.rows = rows;
        return this;
    }
    val(v)
    {
        this.elem.value = v;
        return this;
    }
    focus(f=true)
    {
        this.elem.autofocus = f;
        return this;
    }
}
class Hidden extends Elem
{
    constructor(name, val)
    {
        super('input');
        this.elem.type = 'hidden';
        this.elem.name = name;
        this.elem.value = val;
        return this;
    }
}

class View
{
    uid;
    sep = '-';
    cnf;
    mnu;
    constructor(uid)
    {
        this.uid = uid;
        this.cnf = new Confirm();
        console.log('con', this.cnf);
        this.mnu = new Div().class('mn bottom').bd();
    }
    url(trg, ...params)
    {
        return trg + '?' + [ this.uid, ...params].join(this.sep);
    }
    go(trg, ...params)
    {
        window.location.replace(this.url(trg, ...params));
    }
    view(...params)
    {
        this.go('/', ...params);
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

    confirmLink(icl, func)
    {
        const _this = this;
        new ImgLink(icl).into(this.mnu).click(function() { _this.cnf.show(icl, func); });
    }
    mnuLink(icl, func)
    {
        new ImgLink(icl).into(this.mnu).click(func);
    }
}

class Confirm
{
    dc;
    ai;
    constructor()
    {
        const _this = this;
        const dc = new Div().class('conf_main').bd();
        // darken layer
        new Div().class('conf_bg').into(dc).click( function () {_this.hide(); });
        // vertical layer
        const d1 = new Div().class('conf_fg grow_up').into(dc).click( function () {_this.hide(); });
        // horizontal center
        const d2 = new Div().class('center').into(d1);
        // image button
        this.ai = new ImgLink('').into(d2);

        this.dc = dc;
    }
    hide()
    {
        this.dc.remove('v');
    }
    show(icl, func)
    {
        this.ai.set(icl).click(func);
        this.dc.add('v');
    }
}

class Menu extends View
{
    constructor(uid, entries)
    {
        super(uid);
        const _this = this;
        let done = [];
        let post = [];
        const dl = new Div().class('listing').bd();
        for (const [cnr, ttl, cl] of entries)
        {
            const a = new TxtLink(ttl).class('p').add(cl).click(function() { _this.view(cnr); })
            if      (cl == 'y') post.push(a);
            else if (cl == 'x') done.push(a);
            else a.into(dl);
        }
        for (const a of post) a.into(dl);
        for (const a of done) a.into(dl);

        this.mnuLink('edit', function() { _this.view('e'); });
    }
}

class Toggle
{
    elem;
    constructor(elem, cl)
    {
        this.elem = elem;
        elem.add(cl);
    }
    set(cl)
    {
        this.clear();
        this.elem.add(cl);
    }
    clear()
    {
        this.elem.remove('x', 'y');
    }
    cl()
    {
        return this.elem.has('y') ? 'y' : this.elem.has('x') ? 'x' : '';
    }
    x()
    {
        if (this.elem.has('y')) this.clear();
        else this.elem.toggle('x');
    }
    y()
    {
        if (this.elem.has('x')) this.set('y');
        else this.elem.toggle('y');
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
        const di = new Div().class('item').into(par);
        this.tgl = new Toggle(di, cl);
        new TxtLink(ttl).class('a1').into(di).click(
            function()
            {
                _this.tgl.x();
                _this.note();
            }
        );
        new Link().class('a2').into(di).click(
            function()
            {
                _this.tgl.y();
                _this.note();
            }
        );
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

class Items extends View
{
    cnr;
    top;
    items = [];
    constructor(uid, data)
    {
        super(uid);
        const [ cnr, hl, cl, entries ] = data;
        const _this = this;
        this.cnr = cnr;

        const dl = new Div().class('listing').bd();

        const a = new TxtLink(hl).class('p items top').into(dl).click(function() { _this.view(); });
        this.top = new Toggle(a, cl);

        let inr = 0;
        for (const e of entries)
        {
            if (Array.isArray(e))
            {
                this.items.push(new Item(this, inr, e, dl).toggle());
                ++inr;
            }
            else if (e) new H2(e).into(dl);
            else new HR().into(dl);
        }
        this.confirmLink('remove', function() { _this.remove(); });
        this.confirmLink('reset',  function() { _this.reset();  });
        this.mnuLink('home', function() { _this.view(); });
    }

    note(inr, cl)
    {
        this.send('_state.php', cl, this.cnr, inr);
        const clo = this.top.cl();
        this.top.clear();
        let cln = ''
        if (cl)
        {
            let cnt = {'x':0, 'y':0};
            for (const i of this.items) ++cnt[i.cl()];
            const cx = cnt['x'];
            const cy = cnt['y'];
            cln = cx + cy < this.items.length ? '' : cy > 0 ? 'y' : 'x';
            this.top.set(cln);
        }
        if (cln != clo) this.send('_state.php', cln, this.cnr);
    }
    remove()
    {
        this.go('remove.php', this.cnr);
    }
    reset()
    {
        this.top.clear();
        for (const i of this.items) i.clear();
        this.send('_reset.php', this.cnr);
    }
}

class InputForm extends View
{
    constructor(uid, txt)
    {
        super(uid);
        const _this = this;
        const frm = new Form('save.php').bd();

        new TextArea('txt', 50).class('txt').val(txt).into(frm).focus();

        new Hidden('uid', this.uid).into(frm);

        this.mnuLink('home', function() { _this.view(); });
        this.mnuLink('save', function() { frm.submit(); });
    }
}
