<div class="filter-bar">
  <input type="text" id="q" placeholder="Search items..." />
  <label for="sort">Sort:</label>
  <select id="sort">
    <option value="popularity">Popularity</option>
    <option value="performance">Performance</option>
    <option value="ease">Ease of learning</option>
    <option value="newest">Newest</option>
  </select>

  <label for="min_performance">Min Performance:</label>
  <select id="min_performance">
    <option value="0">Any</option>
    <option value="3">3+</option>
    <option value="4">4+</option>
    <option value="5">5</option>
  </select>

  <label for="learning_curve">Learning Curve:</label>
  <select id="learning_curve">
    <option value="">Any</option>
    <option value="easy">Easy</option>
    <option value="medium">Medium</option>
    <option value="hard">Hard</option>
  </select>

  <button id="apply-filters">Apply</button>
</div>

<script>
// This filter bar is driven by client JS. Category page (`category.js`) will read these controls.
document.getElementById('apply-filters').addEventListener('click', function(){
  const ev = new CustomEvent('tn:filters:change', { detail: {
    q: document.getElementById('q').value,
    sort: document.getElementById('sort').value,
    min_performance: document.getElementById('min_performance').value,
    learning_curve: document.getElementById('learning_curve').value
  }});
  console.log('Filter event dispatched (tags removed):', ev.detail);
  window.dispatchEvent(ev);
});
</script>