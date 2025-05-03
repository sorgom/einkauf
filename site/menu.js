'use strict';

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
            console.log('menu', cnr, ttl, cl);
            const a = tLink(ttl, undefined);
            a.onclick = function() { _this.view(cnr); }
            if (cl)
            {
                console.log(cl);
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
    cl()
    {
        return this.cl;
    }
    x()
    {
        const cll = this.di.classList;
        if (cll.contains('y')) cll.remove('y');
        else cll.toggle('x');
        this.cl = sClass(this.di);
        this.note();
    }
    y()
    {
        const cll = this.di.classList;
        cll.remove('x');
        cll.toggle('y');
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
        const data = JSON.parse(txt);
        const [ uid, cnr, hl, cl, entries ] = data;
        console.log('Items', cnr, hl);
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
        console.log(inr, cl);
        this.send('_state', cl, this.cnr, inr);
        let cnt = { 'x':0, 'y':0};
        for (const i of this.items)
        {
            ++cnt[i.cl];
        }
        const cx = cnt['x'];
        const cy = cnt['y'];
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
        p(ttl, di);
        const dm = div(di);
        dm.className = 'mn';
        const a1 = iLink('back', dm);
        a1.onclick = function () { _this.hide(); }
        const a2 = iLink('remove', dm);
        a2.onclick = function () {
            console.log('clicked');
            _this.go('remove', cnr);
        }
        this.dc = dc;
    }
    hide() { this.dc.classList.remove('v'); }
    show() { this.dc.classList.add('v'); }
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
        console.log('process derived');
        const _this = this;
        const frm = document.createElement('form');
        frm.action = 'save.php';
        frm.method = 'post';

        const d = div();
        d.className = 'mn bottom';
        const a = iLink('back', d);
        a.onclick = function() { _this.view(); }
        const b = iLink('save', d);
        b.onclick = function() { frm.submit(); }

        textarea(frm, 50, 'txt', txt);

        hidden(frm, 'uid', this.uid);
        document.body.appendChild(frm);
    }
}
