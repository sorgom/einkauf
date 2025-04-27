function rumpel(data)
{
    let trg = document.getElementById("demo");
    const [ uid, cnr, items] = data;
    let inr = 0;
    for (const item of items)
    {
        const c = item[0]
        if (!c)
        {
            trg.appendChild(document.createElement('hr'));
        }
        else if (c[0] == '#')
        {
            let h = document.createElement('h2');
            h.innerHTML = c.substring(2);
            trg.appendChild(h);
        }
        else
        {
        let d = document.createElement('div');
        d.classList = item[1].join(' ');
        d.id = cnr + '.' + inr;
        let a = document.createElement('a');
        a.onclick = function() { toggle(d, 'x') };
        let p = document.createElement('p');
        p.innerHTML = item[0];
        a.appendChild(p);
        let b = document.createElement('a');
        b.innerHTML = 'toggle Y';
        b.onclick = function() { toggle(d, 'y') };
        d.appendChild(a);
        d.appendChild(b);
        trg.appendChild(d);
        ++inr;
        }
    }
}

function toggle(obj, cl)
{
    const clo = obj.className;
    obj.classList.toggle(cl);
    if (obj.classList.contains('y'))
        obj.classList.remove('x');
    if (clo != obj.className)
        console.log('send', obj.id, cl, '|', obj.className);
}
