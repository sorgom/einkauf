var usr;

function setusr(u)
{
    usr = u;
}

function ck(obj) 
{
    var p = obj.parentElement;
    p.classList.toggle('x');
    var checked = p.className == 'x' ? 1 : 0;
    var id = p.id;
    // console.log('id: ', id, ' checked: ', checked);
    var data = "usr=" + usr + "&id=" + id + "&ck=" + checked;
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "check.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send(data);
}

function checkinput(obj, bid)
{
    var but = document.getElementById(bid);
    if (!but) return;
    var txt = obj.value.trim();
    but.disabled = !txt.length;
}