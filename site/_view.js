
var numElems = 0;
var numDone = 0;
var head = undefined;
var done = {
    'x': 0,
    'y': 0,
    '' : 0
};
var uid = undefined;
var items = [];

function gen_items(data)
{
    const [ nid, cnr, ttl, lines, states] = data;
    uid = nid;
    const cc = '' + cnr;

        // display of current chapter
    head = tlink(ttl);
    head.id = 'top';
    head.sid = cc;
    head.href = '_index.php?' + uid;
    let cl = states[cc];
    if (cl) head.classList = cl;
    document.body.appendChild(head);

    //  items list
    let trg = document.createElement('div');
    trg.id = 'items';
    let inr = 0;
    for (const c of lines)
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
            let a = tlink(c);
            a.onclick = function() { check(d) };
            d.appendChild(a);
            let b = document.createElement('a');
            b.onclick = function() { lock(d, 'y') };
            d.appendChild(b);

            let cl = states[d.sid];
            if (cl) {
                d.classList = cl;
                ++done[cl];
            }
            trg.appendChild(d);
            items.push(d);
            ++inr;
        }
    }
    document.body.appendChild(trg);

    //  bottom action menu
    let bt = document.createElement('div');
    bt.id = 'bottom';
    bt.className = 'mn';
    let a = ilink('reset');
    a.onclick = reset;
    bt.appendChild(a);
    a = ilink('remove');
    a.href = 'remove.php?' + uid + '&' + cnr;
    bt.appendChild(a);
    document.body.appendChild(bt);

    console.log(document.body.childNodes);

    numElems = items.length;
    console.log('numElems:', numElems);
    console.log('done:', done);
}

function gen_menu(data)
{
    const [ nid, heads, states, empty] = data;
    uid = nid;

    //  items list
    let trg = document.createElement('div');
    trg.id = 'menu';
    let cnr = 0;
    for (const ch of heads)
    {
        let d = document.createElement('div');
        let a = tlink(ch);
        a.href = '_index.php?' + uid + '&' + cnr;
        d.appendChild(a);
        let b = document.createElement('a');
        b.href = '_edit.php?' + uid + '&' + cnr;
        d.appendChild(b);
        let cl = states[cnr];
        if (cl) d.classList.add(cl);
        if (empty.includes(cnr)) d.classList.add('e');
        trg.appendChild(d);
        ++cnr;
    }
    document.body.appendChild(trg);
}

function tlink(ttl)
{
    let p = document.createElement('p');
    p.innerText = ttl;
    let a = document.createElement('a');
    a.appendChild(p);
    return a;
}

function ilink(cl)
{
    let a = document.createElement('a');
    a.className = 'i ' + cl;
    // a.innerText = a.className;
    return a;
}

function check(obj)
{
    const clo = obj.className;
    if (obj.classList.contains('y'))
        obj.className = '';
    else obj.classList.toggle('x');
    eval(obj, clo);
}

function lock(obj)
{
    const clo = obj.className;
    obj.classList.remove('x');
    obj.classList.toggle('y');
    eval(obj, clo);
}

function eval(obj, clo)
{
    const cln = obj.className;
    if (clo != cln)
    {
        --done[clo];
        ++done[cln];
        console.log('eval', obj.sid, obj.className);
        send('_state.php', obj);
        ckh();
    }
}

function ckh()
{
    const clo = head.className;
    const cln = done['x'] + done['y'] < numElems ? '' : done['y'] > 0 ? 'y' : 'x';
    head.className = cln;
    eval(head, clo);
}

function reset()
{
    for (let item of items)
    {
        item.classList = '';
    }
    head.classList = '';
    for (const k in done) done[k] = 0;
    send('_reset.php', head);
}

//  send object state (class)
function send(trg, obj)
{
    var data = 'uid=' + uid + '&id=' + obj.sid + '&st=' + obj.className;
    console.log('send', trg, data);
    var xhr = new XMLHttpRequest();
    xhr.open('POST', trg, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(data);
}
