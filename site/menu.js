
class Menu extends Usr
{
    constructor(data)
    {
        const [ uid, entries ] = data;
        super(uid);
        let _this = this;
        let done = [];
        let post = [];
        const bd = document.body;
        for (const [cnr, ttl, cl] of entries)
        {
            say('menu', cnr, ttl, cl);
            let a = tLink(ttl);
            a.onclick = function() { _this.view(cnr); }
            if (cl)
            {
                say(cl);
                a.classList.add(cl);
                if      (cl == 'y') post.push(a);
                else if (cl == 'x') done.push(a);
            }
            else bd.appendChild(a);
        }
        for (const a of post) bd.appendChild(a);
        for (const a of done) bd.appendChild(a);
        let d = div(bd);
        d.className = 'mn bottom';
        let a = iLink('imprint', d);
        a.onclick = function() { _this.go('imprint'); }
        let b = iLink('edit', d);
        b.onclick = function() { _this.go('edit'); }
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
        let _this = this;
        this.par = par;
        this.inr = inr;
        const [ttl, cl ] = data;
        let di = div(document.body);
        di.className = 'item';
        if (cl)
        {
            di.classList.add(cl);
            this.cl = cl;
        }
        let a1 = tLink(ttl, di);
        a1.className = 'a1';
        a1.onclick = function() { _this.x(); }
        let a2 = anc(di);
        a2.className = 'a2';
        a2.onclick = function() { _this.y(); }
        this.di = di;
        console.log(this.di);
    }
    cl()
    {
        return this.cl;
    }
    x()
    {
        let cll = this.di.classList;
        if (cll.contains('y')) cll.remove('y');
        else cll.toggle('x');
        this.cl = sClass(this.di);
        this.note();
    }
    y()
    {
        let cll = this.di.classList;
        cll.remove('x');
        cll.toggle('y');
        this.cl = sClass(this.di);
        this.note();
    }
    note()
    {
        this.par.note(this.inr, this.cl);
    }
}

class Items extends Usr
{
    cnr;
    top;
    items = [];
    cl = '';
    constructor(data)
    {
        const [ uid, cnr, hl, cl, entries ] = data;
        console.log('Items', cnr, hl);
        super(uid);
        let _this = this;
        this.cnr = cnr;
        const bd = document.body;
        let a = tLink(hl, bd);
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
                let h = make('h2', bd);
                h.innerText = e;
            }
            else make('hr', bd);
        }
    }

    note(inr, cl)
    {
        say(inr, cl);
        this.send(this.cnr + '.' + inr, cl);
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
            this.send(this.cnr, cln);
        }
    }

    send(id, cl)
    {
        var data = 'uid=' + this.uid + '&id=' + id + '&st=' + cl;
        say('data:', data);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '_state.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send(data);
    }
}
