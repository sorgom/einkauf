# js samples
## parse json
```html
<p id="demo"></p>
<script>
function wumpel(jtxt)
{
    const data = JSON.parse(jtxt);
    let trg = document.getElementById("demo");
    for (const item of data)
    {
        trg.innerHTML += 'text: ' + item[0] + ' class: ' + item[1] + '<br>';
    }
}
</script>
<script>wumpel('[\
    [ "wumpel", "e y"], \
    [ "lola", "e y"], \
    [ "zardoz", "x"], \
    [ "seppl", "x"] \
]');</script>
```
