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
    body()
    {
        document.body.appendChild(this.elem);
        return this;
    }
    click(func)
    {
        this.elem.onclick = func;
        return this;
    }
    autofocus()
    {
        this.elem.autofocus = true;
        return this;
    }
    txt(txt)
    {
        this.elem.innerText = txt;
        return this;
    }
    focus()
    {
        this.elem.focus();
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

class Link extends Elem {
    constructor() { return super('a'); }
    href(url)
    {
        this.elem.href = url;
        return this;
    }
}
class P    extends Elem { constructor() { return super('p'); } }
class Div  extends Elem { constructor() { return super('div'); } }
class HR   extends Elem { constructor() { return super('hr'); } }
class H2   extends Elem { constructor() { return super('h2'); } }

class TxtLink extends Link
{
    constructor(txt)
    {
        super();
        new P().txt(txt).into(this);
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
}
class Input extends Elem
{
    constructor(type, name='', val='')
    {
        super('input');
        this.elem.type = type;
        this.elem.name = name;
        this.elem.value = val;
        this.elem.autocomplete = 'on';
        return this;
    }
    required()
    {
        this.elem.required = true;
        return this;
    }
}

class View
{
    uid;
    sep = '-';
    _cnf = undefined;
    _mnu = undefined;

    constructor(uid)
    {
        this.uid = uid;
    }
    url(trg, ...params)
    {
        return trg + '?' + [ this.uid, ...params].join(this.sep);
    }
    go(trg, ...params)
    {
        window.location.assign(this.url(trg, ...params));
    }
    view(...params)
    {
        this.go('/', ...params);
    }
    sendX(task, data)
    {
        const _this = this;
        var xhr = new XMLHttpRequest();
        xhr.onload = () => {
            // In local files, status is 0 upon success in Mozilla Firefox
            if (xhr.readyState === XMLHttpRequest.DONE) {
                const status = xhr.status;
                if (status === 0 || (status >= 200 && status < 400))
                {
                    // - go to login if not 'OK'
                    if (xhr.responseText != 'OK') _this.go('login.php');
                }
            }
        }
        xhr.open('POST', '_states.php', true);
        xhr.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');
        xhr.send(JSON.stringify([this.uid, task, data]));
    }

    mnu()
    {
        if (this._mnu === undefined) this._mnu = new Div().class('mn bottom');
        return this._mnu;
    }

    confirmLink(icl, func)
    {
        if (this._cnf === undefined) this._cnf = new Confirm();
        const _this = this;
        new ImgLink(icl).into(this.mnu()).click(() => { _this._cnf.show(icl, func); });
    }
    mnuLink(icl, func, conf=false)
    {
        new ImgLink(icl).into(this.mnu()).click(func);
    }

    imprint()
    {
        this.mnuLink('imprint', ()=>{ window.location.assign('imprint.php'); });
    }
}

class Confirm
{
    dc;
    ai;
    constructor()
    {
        const _this = this;
        const dc = new Div().class('conf_main').body();
        // darken layer
        new Div().class('conf_bg').into(dc).click(()=>{_this.hide(); });
        // vertical layer
        const d1 = new Div().class('conf_fg grow_up').into(dc).click(()=>{_this.hide(); });
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
    constructor(uid, data)
    {
        super(uid);
        const [encr, entries] = data;
        const _this = this;
        let done = [];
        let post = [];
        const dl = new Div().class('listing').body();
        for (const [cnr, ttl, cl] of entries)
        {
            const a = new TxtLink(ttl).class('p').add(cl).click(()=>{ _this.view(cnr); })
            if      (cl == 'y') post.push(a);
            else if (cl == 'x') done.push(a);
            else a.into(dl);
        }
        for (const a of post) a.into(dl);
        for (const a of done) a.into(dl);
        this.mnu().into(dl);
        this.imprint();
        this.mnuLink('edit',()=>{ _this.view('e'); });
        if (encr) this.confirmLink('logout', ()=>{ _this.go('logout.php'); });
    }
}

class Toggle
{
    elem;
    constructor(elem, cl)
    {
        this.elem = elem;
        this.set(cl);
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
    click()
    {
        switch (this.cl())
        {
        case 'x':
            this.set('y');
            break;
        case 'y':
            this.clear();
            break;
        default:
            this.set('x');
        }
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
        const a = new TxtLink(ttl).class('p item').into(par);
        this.tgl = new Toggle(a, cl);
        a.click(()=>{
            _this.tgl.click();
            _this.note();
        });
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

        const dl = new Div().class('listing').body();

        const a = new TxtLink(hl).class('p items top').into(dl).click(()=>{ _this.view(); });
        this.top = new Toggle(a, cl);

        let inr = 0;
        for (const e of entries)
        {
            if (Array.isArray(e))
            {
                this.items.push(new Item(this, inr, e, dl).toggle());
                ++inr;
            }
            else if (e) new H2(e).txt(e).into(dl);
            else new HR().into(dl);
        }
        this.mnu().into(dl);
        this.confirmLink('remove',()=>{ _this.remove(); });
        this.confirmLink('reset',()=>{ _this.reset();  });
        this.mnuLink('home',()=>{ _this.view(); });
    }

    note(inr, cl)
    {
        const clo = this.top.cl();
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
        else this.top.clear();
        this.sendX('state', [this.cnr, inr, cln, cl]);
    }
    remove()
    {
        this.go('remove.php', this.cnr);
    }
    reset()
    {
        this.top.clear();
        for (const i of this.items) i.clear();
        this.sendX('reset', this.cnr);
    }
}

class InputForm extends View
{
    constructor(uid, txt)
    {
        super(uid);
        const _this = this;
        console.log('InputForm 7');
        const frm = new Form('save.php').body();

        const txa = new TextArea('txt', 50).class('txt').val(txt).into(frm).autofocus();

        new Input('hidden', 'uid', this.uid).into(frm);

        this.mnu().body();
        this.confirmLink('clear',()=>{ txa.val('').focus(); });
        this.mnuLink('home',()=>{ _this.view(); });
        this.mnuLink('save',()=>{ frm.submit(); });
    }
}

class WelcomeForm extends View
{
    constructor()
    {
        super('');
        const frm = new Form('start.php').body();
        const dgr = new Div().class('grow_up').into(frm);
        const dcn = new Div().class('center').into(dgr);
        const din = new Div().into(dcn);
        const dpw = new Div().class('form pwd').into(din);
        new Input('password', 'pwd1').class('frm pwd').autofocus().into(dpw);
        new Input('password', 'pwd2').class('frm pwd spc').into(dpw);
        const dem = new Div().class('form mail').into(din);
        new Input('email', 'em').class('frm').into(dem);
        new Input('submit').class('i enter').into(din);
        this.mnu().body();
        this.imprint();
    }
}

class StartInfo extends View
{
    constructor(uid, data)
    {
        super(uid);
        const _this = this;
        const [ok, addr, link] = data;
        const dgr = new Div().class('grow_up itxt').body();
        if (addr) new Div().class('ico ' + (ok ? 'ok' : 'nok')).txt(addr).into(dgr);
        const dgo = new Div().class('ico go').into(dgr);
        new Link().class('keep').txt(link).into(dgo).click(()=>{ _this.view(); });
        this.mnu().body();
        this.imprint();
    }
}

class LoginForm extends View
{
    constructor(uid, _)
    {
        super(uid);
        const frm = new Form('login.php').body();
        new Input('hidden', 'uid', this.uid).into(frm);
        const dgr = new Div().class('grow_up').into(frm);
        const dcn = new Div().class('center').into(dgr);
        const dpw = new Div().class('form pwd').into(dcn);
        new Input('password', 'pwd').required().autofocus().class('frm pwd').into(dpw);
        new Input('submit').class('i forward').into(dpw);
    }
}

class Imprint extends View
{
    constructor(uid, data)
    {
        super(uid);
        const [txt, branch, date] = data;
        const dg = new Div().class('grow_up').body();
        new Div().class('imprint').txt(txt).into(dg);
        const db = new Div().class('imprint').into(dg);
        new P().into(db).txt('this is open source');
        new Link().class('keep').href('https://github.com/sorgom/todo/tree/' + branch).txt('view on github').into(db);
        const dc = new Div().class('imprint').into(dg);
        // new P().into(dc).txt('commit information');
        new P().into(dc).txt('commit: ' + date);
        new P().into(dc).txt('branch: ' + branch);
        this.mnu().body();
        this.mnuLink('back',()=>{ window.history.back(); });
    }
}
