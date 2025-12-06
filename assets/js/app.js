// Load categories as clickable cards on home page
document.addEventListener('DOMContentLoaded', ()=>{
  const container = document.getElementById('category-list');
  const prefix = (window.__PREFIX__ !== undefined) ? window.__PREFIX__ : '';
  if(container){
    fetch(prefix + 'backend/api/getCategories.php')
      .then(r=>r.json())
      .then(data=>{
        if(data && data.categories){
          data.categories.forEach(c=>{
            const card = document.createElement('div');
            card.className = 'category-card';
            const href = (window.__BASE__ ? window.__BASE__ + '/categories.php?id=' + c.id : 'categories.php?id=' + c.id);
            card.innerHTML = `<a href="${href}"><h3>${c.name}</h3><p>${c.description||'Explore this domain'}</p></a>`;
            container.appendChild(card);
          });
        }
      }).catch((e)=>{ console.error(e); });
  }
});
