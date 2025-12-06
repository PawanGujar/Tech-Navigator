(function(){
  const prefix = (window.__PREFIX__ !== undefined) ? window.__PREFIX__ : '';
  const apiBase = prefix + 'backend/api/';
  function el(s){return document.querySelector(s)}
  function getCompare(){ return JSON.parse(localStorage.getItem('tn_compare')||'[]'); }
  function getScoreClass(score, field) {
    if (field === 'learning_curve') {
      const lc = (score || '').toLowerCase();
      if (lc === 'easy') return 'score-excellent';
      if (lc === 'medium') return 'score-good';
      return 'score-poor';
    }
    if (field === 'performance_score' || field === 'popularity_score') {
      score = parseInt(score);
      if (score >= 4) return 'score-excellent';
      if (score >= 3) return 'score-good';
      return 'score-poor';
    }
    return '';
  }
  function formatField(value, field) {
    if (field === 'learning_curve') {
      return (value || '').charAt(0).toUpperCase() + (value || '').slice(1).toLowerCase();
    }
    if (field === 'performance_score' || field === 'popularity_score') {
      return value ? value + '/5' : 'N/A';
    }
    return value || '-';
  }
  function getTypeIcon(type) {
    const icons = {
      'language': '💻',
      'framework': '⚙️',
      'tool': '🔧'
    };
    return icons[type] || '📦';
  }
  function render(items){
    const root = el('#compare-root');
    if(!items || !items.length) return root.innerHTML = '<p class="compare-empty">No items to compare. Add items from categories (Add to compare).</p>';
    
    let html = '<div class="compare-container"><div class="compare-header">';
    html += '<div class="compare-row-header"><div class="compare-cell-header">Specifications</div>';
    items.forEach(i=> {
      html += `<div class="compare-cell-header">
        <div class="compare-item-card">
          <div class="compare-item-icon">${getTypeIcon(i.type)}</div>
          <div class="compare-item-name">${i.name}</div>
          <div class="compare-item-type">${i.type}</div>
          <div class="compare-item-dev">${i.developer || 'Unknown'}</div>
        </div>
      </div>`;
    });
    html += '</div></div><div class="compare-body">';
    
    const fields = [
      {key: 'type', label: 'Type'},
      {key: 'developer', label: 'Developer'},
      {key: 'performance_score', label: 'Performance'},
      {key: 'popularity_score', label: 'Popularity'},
      {key: 'learning_curve', label: 'Learning Curve'},
      {key: 'release_year', label: 'Release Year'}
    ];
    
    fields.forEach(f=> {
      html += `<div class="compare-row">`;
      html += `<div class="compare-cell compare-label">${f.label}</div>`;
      items.forEach(i=> {
        const scoreClass = getScoreClass(i[f.key], f.key);
        const value = formatField(i[f.key], f.key);
        html += `<div class="compare-cell ${scoreClass}">${value}</div>`;
      });
      html += `</div>`;
    });
    html += '</div></div>';
    root.innerHTML = html;
  }
  async function init(){
    const ids = getCompare();
    if(!ids.length) return render([]);
    const res = await fetch(apiBase + 'compareItems.php?ids=' + encodeURIComponent(ids.join(',')));
    const json = await res.json();
    render(json.items || []);
  }
  document.addEventListener('DOMContentLoaded', init);
})();
