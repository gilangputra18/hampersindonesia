@extends('layouts.admin')

@section('title', 'Daftar Produk')
@section('page_title', 'Kelola Produk')

@section('content')
<style>
  .search-container {
    position: relative;
    flex: 1;
    min-width: 240px;
  }
  .autocomplete-dropdown {
    position: absolute;
    top: calc(100% + 6px);
    left: 0;
    right: 0;
    z-index: 500;
    background: #1e293b;
    border: 1px solid var(--panel-border);
    border-radius: 10px;
    box-shadow: 0 16px 32px rgba(0, 0, 0, 0.5);
    max-height: 380px;
    overflow-y: auto;
    display: none;
  }
  .autocomplete-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 14px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    cursor: pointer;
    transition: background 0.15s;
    text-decoration: none;
    color: var(--text-main);
  }
  .autocomplete-item:last-child {
    border-bottom: none;
  }
  .autocomplete-item:hover, .autocomplete-item.active {
    background: rgba(217, 119, 6, 0.15);
  }
  .autocomplete-item img {
    width: 40px;
    height: 40px;
    object-fit: cover;
    border-radius: 6px;
    background: #334155;
  }
  .autocomplete-item .info {
    flex: 1;
  }
  .autocomplete-item .title {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-main);
  }
  .autocomplete-item .title mark {
    background: rgba(217, 119, 6, 0.35);
    color: #fbbf24;
    border-radius: 2px;
    padding: 0 2px;
  }
  .autocomplete-item .sub {
    font-size: 12px;
    color: var(--text-muted);
    display: flex;
    gap: 8px;
    align-items: center;
    margin-top: 2px;
  }
  .autocomplete-item .price {
    font-size: 13px;
    font-weight: 700;
    color: var(--accent-gold);
  }
  .autocomplete-footer {
    padding: 10px 14px;
    background: rgba(15, 23, 42, 0.6);
    font-size: 12px;
    color: var(--text-muted);
    text-align: center;
    border-top: 1px solid var(--panel-border);
  }
</style>

<div class="panel" style="margin-bottom: 24px;">
  <form id="search-form" action="{{ route('admin.products.index') }}" method="GET" style="display: flex; gap: 16px; flex-wrap: wrap;">
    <div class="search-container">
      <input type="text" id="search-input" name="search" class="form-control" placeholder="Cari nama produk (ketik untuk rekomendasi...)" value="{{ request('search') }}" autocomplete="off">
      <div id="autocomplete-results" class="autocomplete-dropdown"></div>
    </div>
    
    <div style="width: 200px;">
      <select name="category" class="form-control" onchange="this.form.submit()">
        <option value="">Semua Kategori</option>
        @foreach ($categories as $cat)
          <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>
            {{ $cat->title }}
          </option>
        @endforeach
      </select>
    </div>
    
    <button type="submit" class="btn btn-outline">🔍 Cari</button>
    @if (request()->hasAny(['search', 'category']))
      <a href="{{ route('admin.products.index') }}" class="btn btn-outline" style="color: var(--text-muted);">Reset</a>
    @endif
    
    <div style="margin-left: auto; display: flex; gap: 10px; flex-wrap: wrap;">
      <button type="button" class="btn btn-outline" onclick="openCategoryModal()" style="border-color: var(--accent-gold); color: #fef08a;">
        🏷️ Kelola Kategori (Tambah / Hapus)
      </button>
      <a href="{{ route('admin.products.create') }}" class="btn btn-gold">
        ➕ Tambah Produk Baru
      </a>
    </div>
  </form>
</div>

<div class="panel">
  <div class="table-responsive">
    <table class="data-table">
      <thead>
        <tr>
          <th>Foto</th>
          <th>Nama Produk</th>
          <th>Kategori</th>
          <th>Harga</th>
          <th>Tipe / Tag</th>
          <th style="text-align: right;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($products as $p)
          <tr>
            <td>
              <img src="{{ $p->thumbnail_url }}" alt="{{ $p->name }}" class="img-thumb" loading="lazy" decoding="async" onerror="this.onerror=null; this.src='{{ $p->image_url }}'">
            </td>
            <td>
              <strong>{{ $p->name }}</strong>
              <div style="font-size: 11px; color: var(--text-muted);">Slug: {{ $p->slug }}</div>
            </td>
            <td>
              <span style="background: rgba(255,255,255,0.08); padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                {{ $p->category->title ?? '-' }}
              </span>
            </td>
            <td><strong>Rp {{ number_format($p->price, 0, ',', '.') }}</strong></td>
            <td>
              @if ($p->is_best_seller)
                <span style="background: rgba(245, 158, 11, 0.2); color: #f59e0b; padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;">⭐ Best Seller</span>
              @endif
              @if ($p->is_treat)
                <span style="background: rgba(16, 185, 129, 0.2); color: #10b981; padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: 600; margin-left: 4px;">🧁 Treat</span>
              @endif
            </td>
            <td style="text-align: right;">
              <div style="display: inline-flex; gap: 8px;">
                <a href="{{ route('admin.products.edit', $p->id) }}" class="btn btn-outline" style="padding: 6px 12px; font-size: 12px;">
                  ✏️ Edit
                </a>
                <form id="delete-form-{{ $p->id }}" action="{{ route('admin.products.destroy', $p->id) }}" method="POST" style="display: inline;">
                  @csrf
                  @method('DELETE')
                  <button type="button" class="btn btn-danger" style="padding: 6px 12px; font-size: 12px;"
                    onclick="openDeleteModal({{ $p->id }}, '{{ addslashes($p->name) }}')">
                    🗑️ Hapus
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 40px;">
              Tidak ada produk yang ditemukan.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div style="margin-top: 24px;">
    {{ $products->links('partials.pagination') }}
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const searchInput = document.getElementById('search-input');
  const resultsBox = document.getElementById('autocomplete-results');
  const searchForm = document.getElementById('search-form');
  let debounceTimer = null;
  let activeIndex = -1;

  function highlightMatch(text, query) {
    if (!query) return text;
    const regex = new RegExp(`(${query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
    return text.replace(regex, '<mark>$1</mark>');
  }

  function fetchSuggestions(query) {
    if (query.trim().length === 0) {
      resultsBox.style.display = 'none';
      resultsBox.innerHTML = '';
      return;
    }

    fetch(`{{ route('admin.products.autocomplete') }}?q=${encodeURIComponent(query)}`, {
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(res => res.json())
    .then(data => {
      if (!data || data.length === 0) {
        resultsBox.innerHTML = `
          <div style="padding: 14px; text-align: center; font-size: 13px; color: var(--text-muted);">
            Tidak ada produk yang sesuai dengan "<strong>${query}</strong>"
          </div>`;
        resultsBox.style.display = 'block';
        return;
      }

      let html = '';
      data.forEach((p, idx) => {
        const highlightedTitle = highlightMatch(p.name, query);
        html += `
          <div class="autocomplete-item" data-index="${idx}" data-edit-url="${p.edit_url}" data-name="${p.name}">
            <img src="${p.thumbnail_url || p.image_url}" alt="${p.name}" loading="lazy" decoding="async" onerror="this.src='{{ asset('images/cat-cakes.jpg') }}'">
            <div class="info">
              <div class="title">${highlightedTitle}</div>
              <div class="sub">
                <span style="background: rgba(255,255,255,0.1); padding: 2px 6px; border-radius: 4px;">${p.category_title}</span>
              </div>
            </div>
            <div class="price">${p.price_formatted}</div>
          </div>`;
      });

      html += `
        <div class="autocomplete-footer">
          💡 Tekan <strong>Enter</strong> untuk melihat hasil pencarian lengkap
        </div>`;

      resultsBox.innerHTML = html;
      resultsBox.style.display = 'block';
      activeIndex = -1;

      // Add click handlers for items
      document.querySelectorAll('.autocomplete-item').forEach(item => {
        item.addEventListener('click', function (e) {
          const editUrl = this.getAttribute('data-edit-url');
          if (editUrl) {
            window.location.href = editUrl;
          }
        });
      });
    })
    .catch(err => {
      console.error('Autocomplete error:', err);
    });
  }

  searchInput.addEventListener('input', function () {
    clearTimeout(debounceTimer);
    const query = this.value;
    debounceTimer = setTimeout(() => {
      fetchSuggestions(query);
    }, 150);
  });

  searchInput.addEventListener('focus', function () {
    if (this.value.trim().length > 0) {
      fetchSuggestions(this.value);
    }
  });

  // Keyboard navigation support
  searchInput.addEventListener('keydown', function (e) {
    const items = resultsBox.querySelectorAll('.autocomplete-item');
    if (!items.length || resultsBox.style.display === 'none') return;

    if (e.key === 'ArrowDown') {
      e.preventDefault();
      activeIndex = (activeIndex + 1) % items.length;
      updateActiveItem(items);
    } else if (e.key === 'ArrowUp') {
      e.preventDefault();
      activeIndex = (activeIndex - 1 + items.length) % items.length;
      updateActiveItem(items);
    } else if (e.key === 'Enter') {
      if (activeIndex >= 0 && items[activeIndex]) {
        e.preventDefault();
        const editUrl = items[activeIndex].getAttribute('data-edit-url');
        if (editUrl) {
          window.location.href = editUrl;
        }
      }
    } else if (e.key === 'Escape') {
      resultsBox.style.display = 'none';
    }
  });

  function updateActiveItem(items) {
    items.forEach((item, i) => {
      if (i === activeIndex) {
        item.classList.add('active');
        item.scrollIntoView({ block: 'nearest' });
      } else {
        item.classList.remove('active');
      }
    });
  }

  // Hide dropdown on click outside
  document.addEventListener('click', function (e) {
    if (!searchInput.contains(e.target) && !resultsBox.contains(e.target)) {
      resultsBox.style.display = 'none';
    }
  });
});
</script>

<!-- Premium Delete Confirmation Modal -->
<div id="delete-modal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.7); backdrop-filter:blur(4px); align-items:center; justify-content:center;">
  <div style="background:#1e293b; border:1px solid #ef4444; border-radius:14px; padding:36px; max-width:420px; width:90%; text-align:center; box-shadow:0 25px 60px rgba(0,0,0,0.6);">
    <div style="font-size:52px; margin-bottom:16px;">🗑️</div>
    <h3 style="font-family:'Playfair Display',serif; font-size:20px; color:#fff; margin-bottom:10px;">Hapus Produk?</h3>
    <p style="font-size:13px; color:#94a3b8; margin-bottom:8px;">Anda akan menghapus produk:</p>
    <p id="delete-product-name" style="font-size:15px; font-weight:700; color:#fbbf24; margin-bottom:24px; padding:10px 16px; background:rgba(245,158,11,0.1); border-radius:8px; border:1px solid rgba(245,158,11,0.2);"></p>
    <p style="font-size:12px; color:#ef4444; margin-bottom:28px;">⚠️ Tindakan ini tidak dapat dibatalkan!</p>
    <div style="display:flex; gap:12px; justify-content:center;">
      <button onclick="closeDeleteModal()" style="padding:10px 24px; background:rgba(255,255,255,0.08); color:#cad8d1; border:1px solid rgba(255,255,255,0.15); border-radius:6px; font-size:13px; font-weight:600; cursor:pointer;">❌ Batal</button>
      <button onclick="confirmDelete()" style="padding:10px 24px; background:linear-gradient(135deg,#dc2626,#ef4444); color:#fff; border:none; border-radius:6px; font-size:13px; font-weight:700; cursor:pointer; box-shadow:0 4px 14px rgba(239,68,68,0.3);">✅ Ya, Hapus</button>
    </div>
  </div>
</div>

<script>
let _deleteProductId = null;
function openDeleteModal(id, name) {
  _deleteProductId = id;
  document.getElementById('delete-product-name').textContent = name;
  document.getElementById('delete-modal').style.display = 'flex';
}
function closeDeleteModal() {
  _deleteProductId = null;
  document.getElementById('delete-modal').style.display = 'none';
}
function confirmDelete() {
  if (_deleteProductId) {
    document.getElementById('delete-form-' + _deleteProductId).submit();
  }
}
document.getElementById('delete-modal').addEventListener('click', function(e) {
  if (e.target === this) closeDeleteModal();
});
</script>

<!-- Modal Kelola Kategori (Tambah & Hapus Kategori) -->
<div id="category-modal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.8); backdrop-filter:blur(5px); align-items:center; justify-content:center; padding:16px;">
  <div style="background:#1e293b; border:1px solid var(--accent-gold); border-radius:16px; padding:28px; max-width:620px; width:100%; box-shadow:0 25px 60px rgba(0,0,0,0.8); max-height:90vh; overflow-y:auto;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; border-bottom:1px solid var(--panel-border); padding-bottom:14px;">
      <div>
        <h3 style="font-size:18px; font-weight:700; color:#fff; margin:0;">🏷️ Kelola Kategori Produk</h3>
        <p style="font-size:12px; color:var(--text-muted); margin:4px 0 0;">Tambah kategori baru atau hapus kategori yang sudah tidak digunakan.</p>
      </div>
      <button onclick="closeCategoryModal()" style="background:none; border:none; color:#fff; font-size:24px; cursor:pointer;">✕</button>
    </div>

    <!-- Form Tambah Kategori Baru -->
    <div style="background:rgba(15, 23, 42, 0.7); border:1px solid var(--panel-border); border-radius:10px; padding:18px; margin-bottom:24px;">
      <h4 style="font-size:14px; font-weight:700; color:#fef08a; margin-bottom:12px;">➕ Tambah Kategori Baru</h4>
      <form action="{{ route('admin.categories.store') }}" method="POST" style="display:grid; grid-template-columns:1fr 1fr auto; gap:12px; align-items:flex-end;">
        @csrf
        <div>
          <label style="display:block; font-size:11.5px; color:var(--text-muted); margin-bottom:4px;">Nama Kategori</label>
          <input type="text" name="title" class="form-control" placeholder="Contoh: Hampers Lebaran" required style="padding:9px 12px; font-size:13px;">
        </div>
        <div>
          <label style="display:block; font-size:11.5px; color:var(--text-muted); margin-bottom:4px;">Slug URL (Opsional)</label>
          <input type="text" name="slug" class="form-control" placeholder="hampers-lebaran" style="padding:9px 12px; font-size:13px;">
        </div>
        <button type="submit" class="btn btn-gold" style="padding:9px 16px; font-size:13px; font-weight:700; white-space:nowrap;">
          💾 Simpan
        </button>
      </form>
    </div>

    <!-- Daftar Kategori Saat Ini -->
    <div>
      <h4 style="font-size:14px; font-weight:700; color:#fff; margin-bottom:12px;">📋 Daftar Kategori Saat Ini ({{ $categories->count() }})</h4>
      <div style="display:flex; flex-direction:column; gap:8px;">
        @forelse ($categories as $cat)
          <div style="display:flex; justify-content:space-between; align-items:center; background:rgba(255,255,255,0.03); border:1px solid var(--panel-border); border-radius:8px; padding:12px 16px;">
            <div>
              <strong style="color:#fff; font-size:14px;">{{ $cat->title }}</strong>
              <div style="font-size:11.5px; color:var(--text-muted); margin-top:2px;">
                Slug: <code>{{ $cat->slug }}</code> • 
                <span style="color:#38bdf8;">{{ $cat->products()->count() }} produk</span>
              </div>
            </div>

            <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori {{ addslashes($cat->title) }}?');" style="margin:0;">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-outline" style="padding:6px 12px; font-size:12px; color:#f87171; border-color:rgba(239, 68, 68, 0.4);" title="Hapus Kategori">
                🗑️ Hapus
              </button>
            </form>
          </div>
        @empty
          <div style="text-align:center; padding:20px; color:var(--text-muted); font-size:13px;">
            Belum ada kategori terdaftar.
          </div>
        @endforelse
      </div>
    </div>

    <div style="margin-top:24px; text-align:right;">
      <button onclick="closeCategoryModal()" class="btn btn-outline" style="font-size:13px;">Tutup</button>
    </div>
  </div>
</div>

<script>
function openCategoryModal() {
  document.getElementById('category-modal').style.display = 'flex';
}
function closeCategoryModal() {
  document.getElementById('category-modal').style.display = 'none';
}
document.getElementById('category-modal').addEventListener('click', function(e) {
  if (e.target === this) closeCategoryModal();
});
</script>
@endsection
