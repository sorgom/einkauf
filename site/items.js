var numElems = 0;
var numDone = 0;
var head = undefined;
var items = [];

function gen(data)
{
    const [ nid, cnr, ttl, lines, states] = data;
    uid = nid;
    const cc = '' + cnr;

        // display of current chapter
    head = tlink(document.body, ttl);
    head.id = 'ch';
    head.sid = cc;
    head.href = '/?' + uid;
    let cl = states[cc];
    if (cl) head.classList = cl;
    // document.body.appendChild(head);

    //  items list
    let trg = div();
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
            let d = div(trg);
            d.sid = cc + '.' + inr;
            let a = tlink(d, c);
            a.className = 'a1';
            a.onclick = function() { check(d) };
            let b = anc(d);
            b.onclick = function() { lock(d, 'y') };
            b.className = 'a2';

            let cl = states[d.sid];
            if (cl) d.className = cl;

            // trg.appendChild(d);
            items.push(d);
            ++inr;
        }
    }
    document.body.appendChild(trg);

    //  bottom action menu
    let bt = mn_bottom();
    let a = ilink(bt, 'reset');
    a.onclick = resetAll;
    a = ilink(bt, 'remove');
    a.href = 'remove.php?' + uid + '&' + cnr;
    a = ilink(bt, 'edit');
    a.href = 'edit.php?' + uid + '&' + cnr;

    console.log(document.body.childNodes);

    numElems = items.length;
    console.log('numElems', numElems);
    ckh();
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

function eval(obj, clo, ck=true)
{
    const cln = obj.className;
    if (clo != cln)
    {
        console.log('eval', obj.sid, obj.className);
        send('_state.php', obj);
        if (ck) ckh();
    }
}

function ckh()
{
    if (numElems == 0) return;
    const clo = head.className;
    let done = {
        'x' : 0,
        'y' : 0
    };
    for (const i of items) ++done[i.className];
    const cln = done['x'] + done['y'] < numElems ? '' : done['y'] > 0 ? 'y' : 'x';
    console.log('ckh', numElems, done);
    head.className = cln;
    eval(head, clo, false);
}

function resetAll()
{
    for (let item of items) reset(item);
    reset(head);
    send('_reset.php', head);
}
