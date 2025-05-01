
class Menu extends Usr
{
    constructor(data)
    {
        const [ uid, entries ] = data;
        super(uid);
        let _this = this;
        let done = [];
        let post = [];
        let bd = document.body;
        for (const [cnr, ttl, cl]  of entries)
        {
            let a = tLink(ttl);
            console.log(cl);
            if (cl) a.classList.add(cl);
            a.onclick = function() { _this.view(cnr); }
            if      (cl == 'y') post.push[a];
            else if (cl == 'x') done.push[a];
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
    inr;
    di;
    constructor(inr, data)
    {
        let _this = this;
        this.inr = inr;
        const [ttl, cl ] = data;
        let di = div(document.body);
        di.className = 'item';
        if (cl) di.classList.add(cl);
        let a1 = tLink(ttl, di);
        a1.className = 'a1';
        a1.onclick = this.x;
        let a2 = anc(di);
        a2.className = 'a2';
        a1.onclick = this.y;
        this.di = di;
    }
    cl()
    {
        return sClass(this.di);
    }
    x()
    {
        let cll = this.di.classList;
        if (cll.contains('y')) cll.remove('y');
        else cll.toggle('x');
    }
    y()
    {
        let cll = this.di.classList;
        cll.remove('x');
        cll.toggle('y');
    }
}

class Items extends Usr
{
    cnr;
    constructor(data)
    {
        const [ uid, cnr, entries ] = data;
        super(uid);
        this.cnr = cnr;

    }


}
