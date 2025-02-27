var uid;
var numItems = undefined;
var numDone = 0;
var btState = undefined;

function setUid(u)
{
    uid = u;
}

function getNums()
{
    if (numItems === undefined)
    {
        var cont = document.getElementById('items');
        var items = cont.getElementsByTagName('a');
        numItems = items.length;
        numDone = 0;
        for (var item of items)
        {
            if (item.classList.contains('x')) ++numDone;
        }
        btState = document.getElementById('state');
    }
}

function sendId(id, checked)
{
    var data = 'uid=' + uid + '&id=' + id + '&ck=' + (checked ? 1 : 0);
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'done.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(data);
}

function chapId(id)
{
    return id.match(/(\d+)\./)[1];
}

function ck(obj)
{
    getNums();
    obj.classList.toggle('x');
    var checked = obj.classList.contains('x');
    sendId(obj.id, checked);

    numDone += checked ? 1 : -1;
    if (numDone == numItems)
    {
        btState.classList.add('x');
        sendId(chapId(obj.id), true);
    }
    else if (!checked)
    {
        btState.classList.remove('x');
    }
}
