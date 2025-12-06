export function openModal(html){ const modal = document.querySelector('.modal'); if(!modal) return; modal.querySelector('.box').innerHTML = html; modal.style.display='flex'; }
export function closeModal(){ const modal = document.querySelector('.modal'); if(modal) modal.style.display='none'; }
