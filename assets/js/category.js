(function(){
  const prefix = (window.__PREFIX__ !== undefined) ? window.__PREFIX__ : '';
  const apiBase = prefix + 'backend/api/';
  const qs = new URLSearchParams(location.search);
  const cid = qs.get('id');

  function el(sel){ return document.querySelector(sel); }
  function createCard(it){
    const d = document.createElement('div'); d.className='card';
    d.innerHTML = `<div class="title">${escape(it.name)}</div><div class="meta">${escape(it.type)} • ${escape(it.developer||'')}</div><p>${escape(it.description||'')}</p><div class="card-actions"><a href="${prefix}items.php?id=${it.id}" class="btn small">Details</a> <button data-id="${it.id}" class="btn compare-add">Add to compare</button></div>`;
    return d;
  }
  function escape(s){ return String(s||'').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":"&#39;"}[c])); }

  // fetch items with optional filters
  async function fetchItems(filters){
    filters = filters || {};
    const params = new URLSearchParams();
    if(cid) params.set('category_id', cid);
    if(filters.q) params.set('q', filters.q);
    params.set('sort', filters.sort || 'popularity');
    if(filters.min_performance && parseInt(filters.min_performance, 10) > 0) params.set('min_performance', filters.min_performance);
    if(filters.learning_curve) params.set('learning_curve', filters.learning_curve);
    if(filters.tags) params.set('tags', filters.tags);

    const url = apiBase + 'getItems.php?' + params.toString();
    const res = await fetch(url);
    const json = await res.json();
    return json.items || [];
  }

  function groupByType(items){
    const map = {};
    items.forEach(i=>{ if(!map[i.type]) map[i.type]=[]; map[i.type].push(i); });
    return map;
  }

  function render(items){
    const container = el('#items');
    container.innerHTML = '';
    const grouped = groupByType(items);
    ['language','framework','tool'].forEach(type=>{
      if(!grouped[type]) return;
      const section = document.createElement('section');
      section.className = 'card card-section';
      section.innerHTML = `<h3>${type.charAt(0).toUpperCase()+type.slice(1)}s</h3><div class="cards-list" data-type="${type}"></div>`;
      grouped[type].forEach(it => section.querySelector('.cards-list').appendChild(createCard(it)));
      container.appendChild(section);
    });

    // attach compare handlers
    document.querySelectorAll('.compare-add').forEach(btn=>btn.addEventListener('click', (e)=>{
      const id = e.target.dataset.id; addToCompare(id); e.target.textContent='Added';
    }));
  }

  function addToCompare(id){
    const key='tn_compare';
    const cur = JSON.parse(localStorage.getItem(key)||'[]');
    if(!cur.includes(id)) cur.push(id);
    if(cur.length>4) cur.shift(); // keep last 4
    localStorage.setItem(key, JSON.stringify(cur));
    document.querySelectorAll('.compare-count').forEach(el=>el.textContent = cur.length);
  }

  async function init(){
    if(!cid){ document.getElementById('items').innerHTML='<p>Please select a category.</p>'; return; }
    // initial render
    const items = await fetchItems();
    render(items);
    // show compare count
    const cur = JSON.parse(localStorage.getItem('tn_compare')||'[]');
    document.querySelectorAll('.compare-count').forEach(el=>el.textContent = cur.length);
    // listen to filter events
    window.addEventListener('tn:filters:change', async function(e){
      console.log('Filter event received:', e.detail);
      const f = e.detail || {};
      console.log('Fetching items with filters:', f);
      const items2 = await fetchItems(f);
      console.log('Items returned:', items2);
      render(items2);
    });
  }

  document.addEventListener('DOMContentLoaded', init);
})();
