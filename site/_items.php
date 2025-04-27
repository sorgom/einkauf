
<!DOCTYPE html>
<html lang=de>
<head>
<title>localhost</title>
<meta charset='UTF-8'>
<!-- <link rel=stylesheet href='view.css'> -->
<link rel=icon type='image/gif' href='img/check_icon.svg'>
<style>
@media (prefers-color-scheme: light) {
    :root {
        --tc: black;
        --tc-done: lightgreen;
        --tc-out: orange;
        --tc-heading: #BBB;
        --tc-link: blue;
        --tc-low: #666;
        --bg: white;
        --bg-button: rgb(236, 236, 236);
        --bg-done: lightgreen;
        --bg-out: orange;
        --bg-alert: rgb(255, 83, 83);
        }
}
@media (prefers-color-scheme: dark) {
    :root {
        --tc: white;
        --tc-done: green;
        --tc-out: darkred;
        --tc-heading: grey;
        --tc-link: blue;
        --tc-low: #AAA;
        --bg: rgb(35, 33, 33);
        --bg-button: rgb(7, 47, 64);
        --bg-done: rgb(7, 76, 37);
        --bg-out: rgb(98, 4, 7);
        --bg-alert: rgb(130, 91, 8);
        }
}

* {
    margin:0;
    padding: 0;
    font-family: sans-serif;
    color: var(--tc);
    overflow-x: clip;
}
@media (orientation: landscape) {
    body { font-size: 3em; }
}

@media (orientation: portrait) {
    body { font-size: 4.5em; }
    * { font-stretch: condensed; }
    div.imprint {
        font-size: 50%;
    }

}
body {
    background-color: var(--bg);
    padding-bottom: 3em;
    background-color: beige;
}

a {
    display: block;
    text-decoration: none;
}
a:hover { cursor: pointer; }
a > p {
    line-height: 90%;
    overflow-x: clip;
}
#items {
    background-color: lightgreen;
    width: 100%;
}
#items > div {
    display: flex;
    flex-direction: row;
    justify-content: left;
    margin: 1em 0 1em 0;
    background-color: yellow;
}
a {
    width: 84%;
    display: flex;
    flex-direction: column;
    justify-content: top;
    min-height: 2em;
    padding: 0.05em;
    padding-left: 1em;
}
#items > div > a:nth-child(2) {
    display: block;
    width: 1em;
    height: 1.2em;
    min-height: 1em;
    border: 3px solid var(--tc-out);
    border-right: none;
    border-radius: 0.25em 0 0 0.25em;
}
#items .x p { color: var(--tc-done) }
#items .y p { color: var(--tc-out) }
#top > .x { background-color: var(--bg-done); }
#top > .y { background-color: var(--bg-out); }

a#top {
    position: sticky;
    top: 0;
    left: 0;
    z-index: 5;
    width: 100%;
    justify-content: center;
    background-color: var(--bg-button);
}

</style>
<script src=_items.js></script>

</head><body>
</script>
<script>display(['som', 1, 'EDEKA Versand-handel',
    [
        "Schinkensalami mit einem Hauch von Knoblauch",
        "lola",
        "# Fleischtheke",
        "zardoz",
        "",
        "seppl"
    ],
    { "1.0":"x", "1.1":"y", "2":"x"}
]);</script>
</body></html>
