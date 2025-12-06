// stub compare UI
function openCompare(ids){
  window.location = '/compare.php?ids=' + encodeURIComponent(ids.join(','));
}
