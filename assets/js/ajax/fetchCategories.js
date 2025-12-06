export async function fetchCategories(){
  const r = await fetch('/backend/api/getCategories.php');
  return r.json();
}
