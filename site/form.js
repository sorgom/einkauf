function textarea(frm, rows, name, val)
{
    let ta = document.createElement('textarea');
    ta.rows = rows;
    ta.name = name;
    ta.value = val;
    ta.classList.add(name);
    frm.appendChild(ta);
    return ta;
}

function hidden(frm, name, val)
{
    let ip = document.createElement('input');
    ip.type = 'hidden';
    ip.name = name;
    ip.value = val;
    frm.appendChild(ip);
    return ip;
}

function Form(data)
{
    const [task, ttl, txt, cnr] = data;
    let frm = document.createElement('form');
    frm.action = 'save.php';
    frm.method = 'post';
    if (task != 'x')
    {
        textarea(frm,  2, 'ttl', ttl);
    }
    textarea(frm, 20, 'txt', txt);

    hidden(frm, 'task', task);
    hidden(frm, 'cnr', cnr);
    hidden(frm, 'uid', uid);


    document.body.appendChild(frm);

    let d = div(document.body);
    d.id = 'top';
    d.className = 'mn';
    bLink(d);
    let a = iLink(d, 'save');
    a.onclick = function() { frm.submit(); }
}
