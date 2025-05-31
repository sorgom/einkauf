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
    id(id)
    {
        this.elem.id = id;
        return this;
    }
    title(ttl)
    {
        this.elem.title = ttl;
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
class P         extends Elem { constructor() { return super('p'); } }
class Div       extends Elem { constructor() { return super('div'); } }
class HR        extends Elem { constructor() { return super('hr'); } }
class H2        extends Elem { constructor() { return super('h2'); } }
class Button    extends Elem
{
    constructor() {
        super('button');
        this.elem.type = 'button';
    }
}

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
        this.elem.value = val;
        if (name)
        {
            this.elem.name = name;
            if (type != 'hidden')
            {
                this.elem.autocomplete = 'on';
                this.elem.id = name;
            }
        }
        return this;
    }
    required()
    {
        this.elem.required = true;
        return this;
    }
    pattern(ptn)
    {
        this.elem.pattern = ptn;
        return this;
    }
}

class Label extends Elem
{
    constructor() { super('label'); }
    for(f)
    {
        this.elem.for = f;
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
        const d1 = new Div().class('conf_fg middle').into(dc).click(()=>{_this.hide(); });
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

class Overview extends View
{
    constructor(uid, entries)
    {
        super(uid);
        const _this = this;
        let done = [];
        let post = [];
        const dl = new Div().class('middle').body();
        for (const [lnr, ttl, cl] of entries)
        {
            const a = new TxtLink(ttl).class('m').add(cl).click(()=>{ _this.view(lnr); })
            if      (cl == 'y') post.push(a);
            else if (cl == 'x') done.push(a);
            else a.into(dl);
        }
        for (const a of post) a.into(dl);
        for (const a of done) a.into(dl);
        this.mnu().into(dl);
        this.imprint();
        this.mnuLink('edit',()=>{ _this.view('e'); });
        this.confirmLink('logout', ()=>{ _this.go('logout.php'); });
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
        const a = new TxtLink(ttl).class('p').into(par);
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

class TodoList extends View
{
    lnr;
    top;
    items = [];
    constructor(uid, data)
    {
        super(uid);
        const [ lnr, hl, cl, entries ] = data;
        const _this = this;
        this.lnr = lnr;

        const dl = new Div().class('middle').body();

        const a = new TxtLink(hl).class('m top').into(dl).click(()=>{ _this.view(); });
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
        this.sendX('state', [this.lnr, inr, cln, cl]);
    }
    remove()
    {
        this.go('remove.php', this.lnr);
    }
    reset()
    {
        this.top.clear();
        for (const i of this.items) i.clear();
        this.sendX('reset', this.lnr);
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

        const txa = new TextArea('txt', 50).class('txt').val(txt).into(frm).autofocus().focus();

        new Input('hidden', 'uid', this.uid).into(frm);

        this.mnu().body();
        this.confirmLink('clear',()=>{ txa.val('').focus(); });
        this.mnuLink('home',()=>{ _this.view(); });
        this.mnuLink('save',()=>{ frm.submit(); });
    }
}

class PwdToggle
{
    button;
    inputs;
    constructor(button, ...inputs)
    {
        console.log(inputs);
        const _this = this;
        this.button = button;
        this.inputs = inputs;
        this.button.txt(lit.pwdView).click(()=>{ _this.toggle();});
    }
    toggle()
    {
        const first = this.inputs[0];
        const getsTxt = first.elem.type == 'password';
        const newType = getsTxt ? 'text' : 'password';
        this.button.txt(getsTxt ? lit.pwdHide : lit.pwdView);
        for (const i of this.inputs)
        {
            i.elem.type = newType;
            //  supported by some browsers
            if (getsTxt) i.elem.setAttribute('writingsuggestions', 'false');
        }
        first.focus();
    }
}

class WelcomeForm extends View
{
    toggle;
    constructor()
    {
        super('');
        const _this = this;
        const dgr = new Div().class('middle').body();
        const dcn = new Div().class('container').into(dgr);
        new Div().class('txt spc_bottom').txt(lit.intro.replace('##SRV', lit.srv)).into(dcn);
        const frm = new Form('start.php').into(dcn);
        new Label().for('pwd1').txt(lit.pwd).into(frm);
        const pwd1 = new Input('password', 'pwd1').required().autofocus().into(frm);
        new Label().for('pwd2').txt(lit.pwd2).into(frm);
        const pwd2 = new Input('password', 'pwd2').required().into(frm);
        const tgl = new Button().into(frm);
        new Label().for('em').txt(lit.mail).into(frm);
        new Input('email', 'em').into(frm);
        new Input('submit', '', lit.register).into(frm);
        this.mnu().body();
        this.imprint();
        this.toggle = new PwdToggle(tgl, pwd1, pwd2);
    }
}

class StartInfo extends View
{
    constructor(uid, link)
    {
        super(uid);
        const _this = this;
        // const [ok, addr, link] = data;
        const dgr = new Div().class('middle').body();
        const dcn = new Div().class('container').into(dgr);
        new Div().class('txt spc_bottom').txt(lit.yourLink).into(dcn);
        new Link().class('keep').txt(link).into(dcn).click(()=>{ _this.view(); });
    }
}

class LoginForm extends View
{
   toggle;
   constructor(uid, _)
    {
        super(uid);
        const dgr = new Div().class('middle').body();
        const dcn = new Div().class('container').into(dgr);
        const frm = new Form('login.php').into(dcn);
        new Label().for('pwd').txt('Passwort').into(frm);
        const pwd = new Input('password', 'pwd').autofocus().required().into(frm);
        const tgl = new Button().into(frm);
        new Input('submit', '', 'OK').into(frm);
        new Input('hidden', 'uid', this.uid).into(frm);
        this.mnu().body();
        this.imprint();
        this.toggle = new PwdToggle(tgl, pwd);
    }
}

class Imprint extends View
{
    constructor(uid, data)
    {
        super(uid);
        const [txt, branch, date] = data;
        const dg = new Div().class('middle').body();
        new Div().class('imprint').txt(txt).into(dg);
        const dc = new Div().class('imprint').into(dg);
        const link = 'https://github.com/sorgom/todo/tree/' + branch + '/site';
        new Link().class('keep').href(link).txt('view on github').into(dc);
        new P().into(dc).txt('branch: ' + branch);
        new P().into(dc).txt('commit: ' + date);
        this.mnu().body();
        this.mnuLink('back',()=>{ window.history.back(); });
    }
}

class Explain extends View
{
    constructor(uid, txt)
    {
        super(uid);
        const dg = new Div().class('middle').body();
        new Div().class('imprint').txt(txt).into(dg);
        this.mnu().body();
        this.mnuLink('back',()=>{ window.history.back(); });
    }
}
