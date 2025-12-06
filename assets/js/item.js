(function(){
  const prefix = (window.__PREFIX__ !== undefined) ? window.__PREFIX__ : '';
  const apiBase = prefix + 'backend/api/';
  const qs = new URLSearchParams(location.search);
  const id = qs.get('id');
  function el(s){return document.querySelector(s)}
  function escape(s){ return String(s||'').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":"&#39;"}[c])); }

  async function fetchDetails(){
    const res = await fetch(apiBase + 'getItemDetails.php?id=' + encodeURIComponent(id));
    return res.json();
  }

  function scoreClass(val){
    const n = parseInt(val) || 0;
    if(n >= 4) return 'score-excellent';
    if(n >= 3) return 'score-good';
    return 'score-poor';
  }

  function renderScoreBar(val){
    const n = parseInt(val) || 0; const pct = Math.round((n/5) * 100);
    return `<div class="score-bar"><div class="score-bar-inner" style="width:${pct}%"></div></div>`;
  }

  async function init(){
    if(!id) return el('#item-root').innerHTML = '<p>No item specified.</p>';
    const data = await fetchDetails();
    if(!data.ok) return el('#item-root').innerHTML = '<p>Not found.</p>';
    const it = data.item;
    const tags = (it.tags || []).map(t=>`<span class="tag-badge">${escape(t.name)}</span>`).join(' ');
    const html = `
      <div class="item-hero card">
        <div class="item-hero-left">
          <h1 class="item-name">${escape(it.name)}</h1>
          <div class="item-meta">${escape(it.type)} • ${escape(it.developer||'Unknown')}</div>
          <div class="item-desc">${escape(it.description||'')}</div>
          <div class="item-tags">${tags}</div>
        </div>
        <div class="item-hero-right">
          <div class="score-row"><div class="score-label">Performance</div><div class="score-value ${scoreClass(it.performance_score)}">${escape(it.performance_score||'-')}/5</div>${renderScoreBar(it.performance_score)}</div>
          <div class="score-row"><div class="score-label">Popularity</div><div class="score-value ${scoreClass(it.popularity_score)}">${escape(it.popularity_score||'-')}/5</div>${renderScoreBar(it.popularity_score)}</div>
          <div class="score-row"><div class="score-label">Learning</div><div class="score-value">${escape(it.learning_curve||'-')}</div></div>
          <div class="score-row"><div class="score-label">Released</div><div class="score-value">${escape(it.release_year||'-')}</div></div>
          <div class="card-actions" style="margin-top:12px"><a href="${prefix}items.php?id=${it.id}" class="btn small">Open Details</a> <button class="btn small compare-add" data-id="${it.id}">Add to compare</button></div>
        </div>
      </div>

      <div class="card pros-cons">
        <div class="pros">
          <h3>Pros</h3>
          <ul id="pros-list"></ul>
        </div>
        <div class="cons">
          <h3>Cons</h3>
          <ul id="cons-list"></ul>
        </div>
      </div>

      <div class="card">
        <h3>Similar Alternatives</h3>
        <div id="alternatives" class="alt-grid"></div>
      </div>
    `;
    el('#item-root').innerHTML = html;

    // compute pros/cons from simple heuristics and tags
    const pros = [];
    const cons = [];
    if(it.performance_score >= 4) pros.push('Good for performance-critical applications');
    if(it.popularity_score >= 4) pros.push('Large ecosystem and community');
    if(it.learning_curve === 'easy' || it.difficulty_level <= 2) pros.push('Easy to get started');
    if(it.learning_curve === 'hard' || it.difficulty_level >= 4) cons.push('Steep learning curve');
    if(it.type === 'language' && it.release_year && it.release_year < 2000) pros.push('Mature language with many proven libraries');
    // tags driven
    if(it.tags){
      it.tags.forEach(t=>{
        if(t.name === 'high-performance') pros.push('Designed for high performance');
        if(t.name === 'beginner-friendly') pros.push('Good for beginners');
      });
    }

    // populate lists
    const prosList = el('#pros-list');
    pros.slice(0,8).forEach(p=>{ const li=document.createElement('li'); li.textContent = p; prosList.appendChild(li); });
    const consList = el('#cons-list');
    cons.slice(0,8).forEach(c=>{ const li=document.createElement('li'); li.textContent = c; consList.appendChild(li); });

    // attach compare handler
    document.querySelectorAll('.compare-add').forEach(btn=>btn.addEventListener('click', (e)=>{ const id = e.target.dataset.id; addToCompare(id); e.target.textContent='Added'; }));

    // render alternatives (API returns separate 'alternatives')
    const alt = data.alternatives || [];
    const div = el('#alternatives');
    alt.forEach(a=>{
      const card = document.createElement('div'); card.className='alt-card';
      card.innerHTML = `<div class="alt-name">${escape(a.name)}</div><div class="alt-desc">${escape(a.description||'')}</div><div class="alt-actions"><a href="${prefix}items.php?id=${a.id}" class="btn small">View</a></div>`;
      div.appendChild(card);
    });
  }
  document.addEventListener('DOMContentLoaded', init);
})();
