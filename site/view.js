var uid;
var numItems = undefined;
var btState  = undefined;
var clicked  = false;
var cont     = undefined;
var topId    = undefined;

function setUid(u)
{
    uid = u;
    cont = document.getElementById('items');
    var items = cont.getElementsByTagName('div');
    numItems = items.length;
    for (var item of items)
    {
        item.onclick = function() { dea(this) };
        item.firstChild.onclick = function() { ck(this) };
    }
    topId = items[0].id.match(/(\d+)\./)[1];
    btState = document.getElementById('state');
    console.log('topId', topId)
    count();
}

//  disable / enable item
function dea(obj)
{
    if (clicked)
    {
        clicked = false;
        return;
    }
    obj.classList.remove('x');
    obj.classList.toggle('y');
    sendId(obj.id, obj.className);
    count();
}

//  check all done
function count()
{
    var cn = btState.className;
    var ix = cont.getElementsByClassName('x');
    var iy = cont.getElementsByClassName('y');
    console.log('count', ix.length, iy.length);
    var ncn = ix.length + iy.length == numItems ? 'x' : '';
    if (ncn != cn)
    {
        btState.className = ncn;
        sendId(topId, ncn);
    }
}

//  send state
function sendId(id, val)
{
    console.log('val:', val);
    var data = 'uid=' + uid + '&id=' + id + '&ck=' + val;
    console.log('data:', data);
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'done.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(data);
}

//  check item
function ck(obj)
{
    clicked = true;
    var p = obj.parentElement;
    if (p.classList.contains('y')) return;

    console.log('ck');
    p.classList.toggle('x');
    sendId(p.id, p.className);
    count();
}
