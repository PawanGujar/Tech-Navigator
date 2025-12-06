<?php
require __DIR__ . '/../components/header.php';
$cfg = require __DIR__ . '/../../config/config.php';
$base = $cfg['base_path'] ?? '';
$assetPrefix = $base ? $base . '/assets' : 'assets';
?>
<link rel="stylesheet" href="<?= $assetPrefix ?>/css/main.css">
<div class="container">
  <h2>Add New Item</h2>
  <div class="card">
    <form id="add-item-form" class="admin-form">
      <div class="admin-form-grid">
        <div class="form-row">
          <label for="name">Name</label>
          <input type="text" id="name" required />
        </div>
        <div class="form-row">
          <label for="type">Type</label>
          <select id="type">
            <option value="language">Language</option>
            <option value="framework">Framework</option>
            <option value="tool">Tool</option>
          </select>
        </div>
        <div class="form-row">
          <label for="category_id">Category</label>
          <select id="category_id">
            <option value="">Loading categories...</option>
          </select>
        </div>
        <div class="form-row">
          <label for="developer">Developer</label>
          <input type="text" id="developer" />
        </div>
        <div class="form-row full">
          <label for="description">Description</label>
          <textarea id="description" rows="4"></textarea>
        </div>
        <div class="form-row">
          <label for="performance_score">Performance (1-5)</label>
          <select id="performance_score">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5" selected>5</option>
          </select>
        </div>
        <div class="form-row">
          <label for="popularity_score">Popularity (1-5)</label>
          <select id="popularity_score">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5" selected>5</option>
          </select>
        </div>
        <div class="form-row">
          <label for="learning_curve">Learning Curve</label>
          <select id="learning_curve">
            <option value="easy">Easy</option>
            <option value="medium">Medium</option>
            <option value="hard">Hard</option>
          </select>
        </div>
        <div class="form-row">
          <label for="difficulty_level">Difficulty Level</label>
          <input type="number" id="difficulty_level" min="1" max="10" value="3" />
        </div>
        <div class="form-row">
          <label for="release_year">Release Year</label>
          <input type="number" id="release_year" value="2025" />
        </div>
        <div class="form-row full">
          <label for="tags">Tags (comma separated)</label>
          <input type="text" id="tags" placeholder="e.g. backend, high-performance" />
        </div>
      </div>
      <div class="form-actions">
        <button class="btn" id="submit-btn" type="submit">Create Item</button>
        <?php if (session_status() === PHP_SESSION_NONE) session_start(); if(!empty($_SESSION['is_admin'])): ?>
          <a href="<?= $base ? $base . '/admin.php?logout=1' : 'admin.php?logout=1' ?>" class="btn small btn-logout">Logout</a>
        <?php endif; ?>
        <span id="add-result" class="add-result"></span>
      </div>
    </form>
  </div>
</div>

<div class="container">
  <h2>Manage Items</h2>
  <div class="card">
    <div id="manage-root">
      <p class="muted">Loading items…</p>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
  const prefix = window.__PREFIX__ || '';
  const apiBase = prefix + 'backend/api/';

  // load categories
  async function loadCategories(){
    try{
      const r = await fetch(apiBase + 'getCategories.php'); const data = await r.json();
      const sel = document.getElementById('category_id'); sel.innerHTML = '';
      if(data && data.categories){ data.categories.forEach(c=>{ const opt = document.createElement('option'); opt.value = c.id; opt.textContent = c.name; sel.appendChild(opt); }); }
      else sel.innerHTML = '<option value="">No categories</option>';
    }catch(e){ document.getElementById('category_id').innerHTML = '<option value="">Error loading</option>'; }
  }

  // ADMIN ITEMS: load and render
  async function loadItems(){
    const root = document.getElementById('manage-root');
    root.innerHTML = '<p class="muted">Loading items…</p>';
    try{
      const res = await fetch(apiBase + 'adminItems.php'); const json = await res.json();
      if(json && json.ok){ renderItems(json.items || []); } else { root.innerHTML = '<p class="muted">Error loading items</p>'; }
    }catch(e){ root.innerHTML = '<p class="muted">Error loading items</p>'; }
  }

  function renderItems(items){
    const root = document.getElementById('manage-root');
    if(!items || items.length===0) { root.innerHTML = '<p class="muted">No items yet.</p>'; return; }
    let html = '<table style="width:100%;border-collapse:collapse"><thead><tr><th style="text-align:left;padding:8px">Name</th><th style="padding:8px">Type</th><th style="padding:8px">Dev</th><th style="padding:8px">Perf</th><th style="padding:8px">Pop</th><th style="padding:8px">Year</th><th style="padding:8px">Actions</th></tr></thead><tbody>';
    items.forEach(it=>{
      html += `<tr data-id="${it.id}" style="border-top:1px solid #eef"><td style="padding:8px">${escapeHtml(it.name)}</td><td style="padding:8px;text-align:center">${escapeHtml(it.type)}</td><td style="padding:8px;text-align:center">${escapeHtml(it.developer||'')}</td><td style="padding:8px;text-align:center">${it.performance_score||''}</td><td style="padding:8px;text-align:center">${it.popularity_score||''}</td><td style="padding:8px;text-align:center">${it.release_year||''}</td><td style="padding:8px;text-align:center"><button class="btn small edit-btn" data-id="${it.id}">Edit</button> <button class="btn small" style="background:#e02424" data-id="${it.id}" data-action="delete">Delete</button></td></tr>`;
    });
    html += '</tbody></table>';
    root.innerHTML = html;
    // attach handlers
    root.querySelectorAll('button[data-action="delete"]').forEach(b=>b.addEventListener('click', onDelete));
    root.querySelectorAll('.edit-btn').forEach(b=>b.addEventListener('click', onEdit));
  }

  function escapeHtml(s){ return String(s||'').replace(/[&<>\"]/g, c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c])); }

  async function onDelete(e){
    const id = e.target.dataset.id; if(!confirm('Delete item #' + id + '?')) return;
    try{
      const res = await fetch(apiBase + 'adminItems.php', {method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({action:'delete', id: id})});
      const j = await res.json(); if(j && j.ok){ loadItems(); } else alert('Error deleting');
    }catch(err){ alert('Error deleting'); }
  }

  // Fill form for editing
  async function onEdit(e){
    const id = e.target.dataset.id;
    try{
      // fetch single item details via existing API: getItemDetails.php
      const res = await fetch(apiBase + 'getItemDetails.php?id=' + encodeURIComponent(id)); const j = await res.json();
      if(j && j.ok && j.item){
        const it = j.item;
        document.getElementById('name').value = it.name || '';
        document.getElementById('type').value = it.type || 'language';
        document.getElementById('category_id').value = it.category_id || '';
        document.getElementById('developer').value = it.developer || '';
        document.getElementById('description').value = it.description || '';
        document.getElementById('performance_score').value = it.performance_score || 5;
        document.getElementById('popularity_score').value = it.popularity_score || 5;
        document.getElementById('learning_curve').value = it.learning_curve || 'easy';
        document.getElementById('difficulty_level').value = it.difficulty_level || 3;
        document.getElementById('release_year').value = it.release_year || '';
        document.getElementById('tags').value = (j.tags || []).map(t=>t.name).join(', ');
        // mark form as editing
        const form = document.getElementById('add-item-form'); form.dataset.editing = id;
        document.getElementById('submit-btn').textContent = 'Update Item';
        window.scrollTo({top:0,behavior:'smooth'});
      } else alert('Failed to load item');
    }catch(err){ alert('Failed to load item'); }
  }

  // submit handler supports create and update
  document.getElementById('add-item-form').addEventListener('submit', async function(e){
    e.preventDefault();
    const form = this; const editingId = form.dataset.editing || null;
    const payload = {
      name: document.getElementById('name').value.trim(),
      type: document.getElementById('type').value,
      category_id: document.getElementById('category_id').value,
      developer: document.getElementById('developer').value.trim(),
      description: document.getElementById('description').value.trim(),
      performance_score: document.getElementById('performance_score').value,
      popularity_score: document.getElementById('popularity_score').value,
      learning_curve: document.getElementById('learning_curve').value,
      difficulty_level: document.getElementById('difficulty_level').value,
      release_year: document.getElementById('release_year').value,
      tags: document.getElementById('tags').value.trim()
    };
    const submitBtn = document.getElementById('submit-btn'); const out = document.getElementById('add-result');
    submitBtn.disabled = true; out.textContent=''; out.className='add-result';
    try{
      if(editingId){
        // update via adminItems.php
        const fields = Object.assign({}, payload);
        const res = await fetch(apiBase + 'adminItems.php', {method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({action:'update', id: editingId, fields: fields, tags: payload.tags})});
        const j = await res.json(); submitBtn.disabled = false;
        if(j && j.ok){ out.classList.add('ok'); out.textContent = 'Updated.'; delete form.dataset.editing; submitBtn.textContent='Create Item'; form.reset(); loadItems(); }
        else { out.classList.add('error'); out.textContent = j.error || 'Update failed'; }
      } else {
        const res = await fetch(apiBase + 'addItem.php', {method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(payload)});
        const json = await res.json(); submitBtn.disabled = false;
        if(json && json.ok){ out.classList.add('ok'); const catHref = (window.__BASE__ ? window.__BASE__ + '/categories.php?id=' + payload.category_id : 'categories.php?id=' + payload.category_id); out.innerHTML = `Created item id: <strong>${json.id}</strong>. <a href="${catHref}">View in category</a>`; form.reset(); loadItems(); }
        else { out.classList.add('error'); out.textContent = (json && json.error) ? json.error : 'Error creating item'; }
      }
    }catch(err){ submitBtn.disabled = false; out.classList.add('error'); out.textContent='Network or server error'; }
  });

  // initial load
  loadCategories(); loadItems();
});
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>
