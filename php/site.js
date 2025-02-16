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
