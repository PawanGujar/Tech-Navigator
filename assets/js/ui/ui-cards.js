export function renderCard(item){
  const div = document.createElement('div');
  div.className = 'card';
  div.innerHTML = `<div class="title">${item.name}</div><div class="meta">${item.type} • ${item.developer||''}</div><p>${item.description||''}</p>`;
  return div;
}
