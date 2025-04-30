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
        const e = new Entry(cnr, ch);
        if (empty.includes(cnr))
        {
            e.add('e');
            e.a1.href = 'edit.php/?' + uid + '&' + cnr;
        }
        else e.a1.href = '/?' + uid + '&' + cnr;
        e.a2.onclick = function () { toolbox(e); };
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
    a = iLink(bt, 'edit');
    a.href = 'input.php?' + uid;
}

function toolbox(e)
{
    // const rect = obj.getBoundingClientRect();
    // console.log(rect.top, rect.right, rect.bottom, rect.left);
    let tb = document.getElementById('tb');
    if (tb) tb.remove();
    const sid = e.sid;
    console.log('TB', e.sid);
    if (tbnr == sid) tbnr = undefined;
    else
    {
        let tb = div();
        tb.className = 'mn';
        tb.id = 'tb';
        let a = iLink(tb, 'reset');
        a.onclick = function () {
            if (e.reset(true))
                window.location.reload();
        }
        a = iLink(tb, 'remove');
        a.onclick = function () { removeConf.show(sid, e.ttl()); }
        a = iLink(tb, 'edit');
        a.href = 'edit.php?' + uid + '&' + sid;
        insertAfter(e.node(), tb);
        tbnr = sid;
    }
}

function resetEntry(obj)
{
    const clo = obj.className;
    reset(obj);
    if (obj.className != clo)
    {
        sendObj('_reset.php', obj);
    }
}
