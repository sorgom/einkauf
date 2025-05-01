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

class Form
{
    constructor(data)
    {
        const [task, ttl, txt, cnr] = data;

        let frm = document.createElement('form');
        frm.action = 'save.php';
        frm.method = 'post';
        let d = div(document.body);
        d.className = 'mn top';
        bLink(d);
        let a = iLink(d, 'save');
        a.onclick = function() { frm.submit(); }

        textarea(frm, 50, 'txt', txt);

        hidden(frm, 'uid', uid);


        document.body.appendChild(frm);

    }
}
