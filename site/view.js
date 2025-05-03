'use strict';

class Usr
{
    uid;
    sep = '|';

    constructor(uid)
    {
        this.uid = uid;
        console.log('uid:', this.uid);
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
    send(trg, ...params)
    {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', this.url(trg, ...params), true);
        xhr.send(null);
    }

    process()
    {
        console.log('process base');
    }

    get(...params)
    {
        console.log('GET');
        const _this = this;
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function()
        {
            if (xhr.readyState == 4 && xhr.status == 200)
            {
                console.log('DATA');
                _this.process(xhr.responseText);
            }
        }
        xhr.open('GET', this.url('_get', ...params), true);
        xhr.send(null);
    }
}

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

function sClass(obj)
{
    const cll = obj.classList;
    return cll.contains('y') ? 'y' : cll.contains('x') ? 'x' : '';
}

function reset(e)
{
    e.classList.remove('x', 'y');
}
