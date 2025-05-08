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
    put(par)
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
        console.log('add:', '-' + cl + '-');
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

class A extends Elem
{
    constructor()
    {
        return super('a');
    }
}

class P extends T_Elem
{
    constructor(txt)
    {
        return super('p', txt);
    }
}

class AT extends A
{
    constructor(txt)
    {
        super();
        new P(txt).put(this);
        return this;
    }
}

class AI extends A
{
    constructor(cl)
    {
        super();
        return this.class('i ' + cl);
    }
}

class Div extends Elem
{
    constructor()
    {
        return super('div');
    }
}


class HR extends Elem
{
    constructor()
    {
        return super('hr');
    }

}
class H2 extends T_Elem
{
    constructor(txt)
    {
        return super('h2', txt);
    }
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
    constructor(rows)
    {
        super('textarea');
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
        const dl = new Div().class('listing').bd();
        for (const [cnr, ttl, cl] of entries)
        {
            console.log('cl:', '-' + cl + '-');
            const a = new AT(ttl).class('p').add(cl).click(function() { _this.view(cnr); })
            if (cl)
            {
                if      (cl == 'y') post.push(a);
                else if (cl == 'x') done.push(a);
            }
            else a.put(dl);
        }
        for (const a of post) a.put(dl);
        for (const a of done) a.put(dl);
        const d = new Div().class('mn bottom').bd();
        new AI('edit').put(d).click(function() { _this.view('e'); });
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
        const di = new Div().class('item').put(par);
        this.tgl = new Toggle(di, cl);
        new AT(ttl).class('a1').put(di).click(
            function()
            {
                _this.tgl.x();
                _this.note();
            }
        );
        new A().class('a2').put(di).click(
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

        const dl = new Div().class('listing').bd();

        const a = new AT(hl).class('p items top').put(dl).click(function() { _this.view(); });
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
                new H2(e).put(dl);
            }
            else new HR().put(dl);
        }
        const d = new Div().class('mn bottom').bd();
        new AI('remove').put(d).click(function() { _this.conf_remove.show(); });
        new AI('reset').put(d).click(function() { _this.conf_reset.show(); });
        new AI('up').put(d).click(function() { _this.view(); });

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
        const dc = new Div().class('confirm').bd().click(function () { _this.hide(); });
        const di = new Div().class('center').put(dc);
        new AI(cl).add('confirm').put(di).click(func);
        this.dc = dc;
    }
    hide()
    {
        this.dc.remove('v');
    }
    show()
    {
        this.dc.add('v');
    }
}

class InputForm extends Usr
{
    constructor(uid, txt)
    {
        super(uid);
        const _this = this;
        const frm = new Form('save.php').bd();

        new TextArea(50).class('txt').focus().val(txt).put(frm);

        new Hidden('uid', this.uid).put(frm);

        const d = new Div().class('mn bottom').bd();
        new AI('back').put(d).click(function() { _this.view(); });
        new AI('save').put(d).click(function() { frm.submit(); });
    }
}
