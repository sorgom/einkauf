
var numElems = 0;
var numDone = 0;
var head = undefined;
var done = {
    'x': 0,
    'y': 0,
    '' : 0
};
var uid = undefined;

function display(data)
{
    const [ nid, cnr, ttl, items, states] = data;
    uid = nid;
    const cc = '' + cnr;
    // display of current chapter
    head = document.createElement('a');
    head.id = 'top';
    head.sid = cc;
    head.href = '/?' + uid;
    let cl = states[cc];
    if (cl) head.classList = cl;
    let p = document.createElement('p');
    p.innerText = ttl;
    head.appendChild(p);
    document.body.appendChild(head);

    //  items list
    let trg = document.createElement('div');
    trg.id = 'items';
    document.body.appendChild(trg);
    let inr = 0;
    for (const c of items)
    {
        if (!c)
        {
            trg.appendChild(document.createElement('hr'));
        }
        else if (c[0] == '#')
        {
            let h = document.createElement('h2');
            h.innerText = c.substring(2);
            trg.appendChild(h);
        }
        else
        {
            let d = document.createElement('div');
            d.sid = cc + '.' + inr;
            let a = document.createElement('a');
            a.onclick = function() { toggle(d, 'x') };
            let p = document.createElement('p');
            p.innerText = c;
            a.appendChild(p);
            d.appendChild(a);
            let b = document.createElement('a');
            b.onclick = function() { toggle(d, 'y') };
            d.appendChild(b);

            let cl = states[d.sid];
            if (cl) {
                d.classList = cl;
                ++done[cl];
            }

            trg.appendChild(d);
            ++inr;
            ++numElems;
        }
    }

    console.log('numElems:', numElems);
    console.log('done:', done);
}

function toggle(obj, cl)
{
    const clo = obj.className;
    obj.classList.toggle(cl);
    if (obj.classList.contains('y'))
        obj.classList.remove('x');
    const cln = obj.className;
    if (clo != cln)
    {
        --done[clo];
        ++done[cln];
        console.log('toggle', obj.sid, cl, '|', obj.className);
        ckh();
    }
}

function ckh()
{
    const clo = head.className;
    const cln = done['x'] + done['y'] < numElems ? '' : done['y'] > 0 ? 'y' : 'x';
    if (clo != cln)
    {
        head.classList = cln;
        console.log('head:', head.className);
    }
}
