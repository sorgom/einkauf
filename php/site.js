var usr;

function setusr(u)
{
    usr = u;
}

function ck(obj) 
{
    var id = obj.id;
    var checked = obj.checked ? 1 : 0;
    var data = "usr=" + usr + "&id=" + id + "&ck=" + checked;
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "check.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send(data);
}

function cc(id) 
{
    var elem = document.getElementById(id);
    var txt = elem.innerText || elem.textContent;
    navigator.clipboard.writeText(txt);
}

function enable(id) 
{
    var elem = document.getElementById(id);
    elem.disabled = false;
}
