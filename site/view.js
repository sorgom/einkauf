
class Usr
{
    uid;
    // TODO: will be replaced by comma
    sep = '&';

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
}

function make(what, par=undefined)
{
    let e = document.createElement(what);
    if (par) par.appendChild(e);
    return e;
}

function div(par=undefined)
{
    return make('div', par);
}

function p(ttl, par=undefined)
{
    let p = make('p', par);
    p.innerText = ttl;
    return p;
}

function anc(par)
{
    return make('a', par);
}

function tLink(ttl, par=undefined)
{
    let a = anc(par);
    a.className = 'p';
    p(ttl, a);
    return a;
}

function iLink(cl, par=undefined)
{
    let a = anc(par);
    a.className = 'i ' + cl;
    return a;
}

function bLink(par=undefined)
{
    let a = iLink(par, 'back');
    a.onclick = function () { history.back(); }
    return a;
}

function sClass(obj)
{
    const cll = obj.classList;
    return cll.contains('y') ? 'y' : cll.contains('x') ? 'x' : '';
}

function say(...c)
{
    console.log(...c);
}
