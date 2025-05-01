
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

function div(par=undefined)
{
    let d = document.createElement('div');
    if (par) par.appendChild(d);
    return d;
}

function p(ttl, par=undefined)
{
    let p = document.createElement('p');
    p.innerText = ttl;
    if (par) par.appendChild(p);
    return p;
}

function anc(par)
{
    let a = document.createElement('a');
    if (par) par.appendChild(a);
    return a;
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
