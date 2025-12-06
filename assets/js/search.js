// stub search
function searchItems(q){
  return fetch(`/backend/api/getItems.php?q=${encodeURIComponent(q)}`).then(r=>r.json());
}
