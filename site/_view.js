

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
    return a;
}

//  bottom action menu
function mn_bottom()
{
    let bt = document.createElement('div');
    bt.id = 'bottom';
    bt.className = 'mn';
    document.body.appendChild(bt);
    return bt;
}

//  bottom action menu
function mn_top()
{
    let bt = document.createElement('div');
    bt.id = 'top';
    bt.className = 'mn';
    document.body.appendChild(bt);
    return bt;
}

function insertAfter(trg, obj)
{
    trg.parentNode.insertBefore(obj, trg.nextSibling);
}

function div()
{
    return document.createElement('div');
}
function anc()
{
    return document.createElement('a');
}

//  send object state (class)
function send(trg, obj)
{
    const cl = obj.classList.contains('y') ? 'y' : obj.classList.contains('x') ? 'x' : '';
    var data = 'uid=' + uid + '&id=' + obj.sid + '&st=' + cl;
    console.log('send', trg, data);
    var xhr = new XMLHttpRequest();
    xhr.open('POST', trg, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(data);
}
