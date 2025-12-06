<?php
function render_item_card($item){
    $t = htmlspecialchars($item['type'] ?? '');
    $name = htmlspecialchars($item['name'] ?? '');
    $desc = htmlspecialchars($item['description'] ?? '');
    return "<div class=\"card\"><div class=\"title\">{$name}</div><div class=\"meta\">{$t}</div><p>{$desc}</p></div>";
}
?>