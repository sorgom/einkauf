entries = [];
uid = undefined;
tbnr = undefined;

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
        let d = document.createElement('div');
        d.sid = cnr;
        let a = tlink(ch);
        if (empty.includes(cnr))
        {
            d.classList.add('e');
            a.href = 'edit.php/?' + uid + '&' + cnr;
        }
        else a.href = '/?' + uid + '&' + cnr;
        d.appendChild(a);
        let b = document.createElement('a');
        b.onclick = function () { toolbox(this); };
        d.appendChild(b);
        let cl = states[cnr];
        if (cl) d.classList.add(cl);
        if (d.classList.contains('y')) out.push(d);
        else if (d.classList.contains('x')) done.push(d);
        else trg.appendChild(d);
        entries.push(d);
        ++cnr;
    }
    for (const d of out) trg.appendChild(d);
    for (const d of done) trg.appendChild(d);

    trg.appendChild(document.createComment('end'));
    document.body.appendChild(trg);

    let bt = mn_bottom();
    a = ilink('edit');
    a.href = 'input.php?' + uid;
    bt.appendChild(a);
}

function toolbox(obj)
{
    let tb = document.getElementById('tb');
    if (tb) tb.remove();
    const par = obj.parentNode;
    const sid = par.sid;
    if (tbnr == sid) tbnr = undefined;
    else
    {
        let tb = div();
        tb.className = 'mn';
        tb.id = 'tb';
        let a = ilink('remove');
        a.href = 'remove.php?' + uid + '&' + sid;
        tb.appendChild(a);
        insertAfter(par, tb);
        tbnr = sid;
    }
}
