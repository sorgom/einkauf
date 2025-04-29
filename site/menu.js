var entries = [];
var tbnr = undefined;

function gen(data)
{
    const [ nid, heads, states, empty] = data;
    uid = nid;

    //  items list
    let trg = document.createElement('div');
    trg.id = 'menu';
    let cnr = 0;
    let done = [];
    let out = [];
    for (const ch of heads)
    {
        var e = new Entry(cnr, ch);
        if (empty.includes(cnr))
        {
            e.add('e');
            e.a1.href = 'edit.php/?' + uid + '&' + cnr;
        }
        else e.a1.href = '/?' + uid + '&' + cnr;
        e.a2.onclick = function () { toolbox(this); };
        let cl = states[cnr];
        if (cl) e.add(cl);
        if (e.has('y')) out.push(e);
        else if (e.has('x')) done.push(e);
        else e.put(trg);
        entries.push(e);
        ++cnr;
    }
    for (const e of out) e.put(trg);
    for (const e of done) e.put(trg);

    trg.appendChild(document.createComment('end'));
    document.body.appendChild(trg);

    let bt = mn_bottom();
    a = ilink(bt, 'edit');
    a.href = 'input.php?' + uid;
}

function toolbox(obj)
{
    let tb = document.getElementById('tb');
    if (tb) tb.remove();
    const par = obj.parentNode;
    const sid = par.sid;
    console.log('TB', par.className);
    if (tbnr == sid) tbnr = undefined;
    else
    {
        let tb = div();
        tb.className = 'mn';
        tb.id = 'tb';
        let a = ilink(tb, 'reset');
        a.onclick = function () { resetEntry(par); }
        a = ilink(tb, 'remove');
        a.href = 'remove.php?' + uid + '&' + sid;
        a = ilink(tb, 'edit');
        a.href = 'edit.php?' + uid + '&' + sid;
        insertAfter(par, tb);
        tbnr = sid;
    }
}

function resetEntry(obj)
{
    const clo = obj.className;
    reset(obj);
    if (obj.className != clo)
    {
        send('_reset.php', obj);
    }
}
