@extends('layouts.admin')

@section('title', 'Pesan Kontak & Concierge')
@section('page_title', 'Pesan Kontak & Concierge')

@section('content')
<style>
  /* Metric Header Cards */
  .stat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 28px;
  }
  .stat-card {
    background: var(--panel-bg);
    border: 1px solid var(--panel-border);
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    text-decoration: none;
    color: inherit;
    transition: all 0.25s ease;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
  }
  .stat-card:hover {
    border-color: var(--accent-gold);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(245, 158, 11, 0.15);
  }
  .stat-card.active {
    border-color: var(--accent-gold);
    background: linear-gradient(135deg, #1e293b 0%, rgba(217, 119, 6, 0.15) 100%);
  }
  .stat-val {
    font-size: 28px;
    font-weight: 700;
    color: #fff;
    line-height: 1.1;
  }
  .stat-lbl {
    font-size: 12px;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-top: 4px;
  }
  .stat-icon {
    font-size: 28px;
    opacity: 0.8;
  }

  /* Filter Toolbar */
  .toolbar-container {
    background: var(--panel-bg);
    border: 1px solid var(--panel-border);
    border-radius: 12px;
    padding: 16px 20px;
    margin-bottom: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
  }
  .filter-pills {
    display: flex;
    gap: 8px;
    align-items: center;
    flex-wrap: wrap;
  }
  .filter-pill {
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 12.5px;
    font-weight: 600;
    color: var(--text-muted);
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid var(--panel-border);
    transition: all 0.2s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }
  .filter-pill:hover, .filter-pill.active {
    color: #fff;
    background: var(--accent-gold);
    border-color: var(--accent-gold);
    box-shadow: 0 4px 12px rgba(217, 119, 6, 0.3);
  }

  /* Message List Table Layout */
  .msg-card-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
  }
  .msg-item {
    background: var(--panel-bg);
    border: 1px solid var(--panel-border);
    border-radius: 12px;
    padding: 20px;
    display: grid;
    grid-template-columns: auto 1fr auto;
    gap: 20px;
    align-items: center;
    transition: all 0.25s ease;
    position: relative;
    overflow: hidden;
  }
  .msg-item:hover {
    border-color: rgba(245, 158, 11, 0.4);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
  }
  .msg-item.unread {
    background: linear-gradient(90deg, rgba(217, 119, 6, 0.08) 0%, var(--panel-bg) 100%);
    border-left: 4px solid var(--accent-gold);
  }
  
  .avatar-circle {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: linear-gradient(135deg, #b45309 0%, #d97706 100%);
    color: #fff;
    font-weight: 700;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-transform: uppercase;
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    flex-shrink: 0;
  }
  .avatar-circle.read {
    background: rgba(255, 255, 255, 0.1);
    color: var(--text-muted);
  }

  .msg-main-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
    min-width: 0;
  }
  .msg-header-row {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
  }
  .sender-name {
    font-size: 15px;
    font-weight: 700;
    color: #fff;
  }
  .sender-email {
    font-size: 12.5px;
    color: var(--text-muted);
  }
  .subject-tag {
    font-size: 11px;
    font-weight: 600;
    padding: 3px 10px;
    border-radius: 12px;
    background: rgba(217, 119, 6, 0.15);
    color: #fef08a;
    border: 1px solid rgba(217, 119, 6, 0.3);
    display: inline-block;
  }

  .msg-snippet {
    font-size: 13.5px;
    color: #cbd5e1;
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin-top: 2px;
  }

  .msg-actions {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
  }
  .date-time {
    font-size: 11.5px;
    color: var(--text-muted);
    text-align: right;
    margin-bottom: 8px;
    white-space: nowrap;
  }
</style>

<!-- Stat Cards Overview Bar -->
<div class="stat-grid">
  <a href="{{ route('admin.contact.index') }}" class="stat-card {{ !request('status') ? 'active' : '' }}">
    <div>
      <div class="stat-val">{{ $totalCount }}</div>
      <div class="stat-lbl">Semua Pesan</div>
    </div>
    <div class="stat-icon">📬</div>
  </a>

  <a href="{{ route('admin.contact.index', ['status' => 'unread']) }}" class="stat-card {{ request('status') === 'unread' ? 'active' : '' }}">
    <div>
      <div class="stat-val" style="color: #f59e0b;">{{ $unreadCount }}</div>
      <div class="stat-lbl">Belum Dibaca (Baru)</div>
    </div>
    <div class="stat-icon">🔴</div>
  </a>

  <a href="{{ route('admin.contact.index', ['status' => 'read']) }}" class="stat-card {{ request('status') === 'read' ? 'active' : '' }}">
    <div>
      <div class="stat-val" style="color: #94a3b8;">{{ $readCount }}</div>
      <div class="stat-lbl">Sudah Dibaca</div>
    </div>
    <div class="stat-icon">⚪</div>
  </a>

  <a href="{{ route('admin.contact.index', ['status' => 'replied']) }}" class="stat-card {{ request('status') === 'replied' ? 'active' : '' }}">
    <div>
      <div class="stat-val" style="color: #10b981;">{{ $repliedCount }}</div>
      <div class="stat-lbl">Telah Dibalas</div>
    </div>
    <div class="stat-icon">✅</div>
  </a>
</div>

@if (session('success'))
  <div style="background: rgba(16, 185, 129, 0.15); border: 1px solid #10b981; color: #34d399; padding: 14px 20px; border-radius: 10px; margin-bottom: 24px; font-size: 14px;">
    ✅ {{ session('success') }}
  </div>
@endif

<!-- Toolbar: Search & Action Buttons -->
<div class="toolbar-container">
  <div class="filter-pills">
    <a href="{{ route('admin.contact.index') }}" class="filter-pill {{ !request('status') ? 'active' : '' }}">
      Semua ({{ $totalCount }})
    </a>
    <a href="{{ route('admin.contact.index', ['status' => 'unread']) }}" class="filter-pill {{ request('status') === 'unread' ? 'active' : '' }}">
      🔴 Belum Dibaca ({{ $unreadCount }})
    </a>
    <a href="{{ route('admin.contact.index', ['status' => 'read']) }}" class="filter-pill {{ request('status') === 'read' ? 'active' : '' }}">
      ⚪ Sudah Dibaca ({{ $readCount }})
    </a>
    <a href="{{ route('admin.contact.index', ['status' => 'replied']) }}" class="filter-pill {{ request('status') === 'replied' ? 'active' : '' }}">
      ✅ Dibalas ({{ $repliedCount }})
    </a>
  </div>

  <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
    <form action="{{ route('admin.contact.index') }}" method="GET" style="display: flex; gap: 8px;">
      @if(request('status'))
        <input type="hidden" name="status" value="{{ request('status') }}">
      @endif
      <input type="text" name="q" class="form-control" placeholder="Cari nama, email, pesan..." value="{{ request('q') }}" style="width: 220px; padding: 8px 12px; font-size: 13px;">
      <button type="submit" class="btn btn-gold" style="padding: 8px 14px; font-size: 12px;">Cari</button>
      @if(request('q'))
        <a href="{{ route('admin.contact.index', ['status' => request('status')]) }}" class="btn btn-outline" style="padding: 8px 12px; font-size: 12px;">Reset</a>
      @endif
    </form>

    @if ($unreadCount > 0)
      <form action="{{ route('admin.contact.mark_all_read') }}" method="POST" style="margin: 0;">
        @csrf
        <button type="submit" class="btn btn-outline" style="padding: 8px 14px; font-size: 12px; border-color: rgba(245, 158, 11, 0.4); color: #fef08a;">
          ✓ Tandai Semua Dibaca
        </button>
      </form>
    @endif
  </div>
</div>

<!-- Messages List Section -->
<div class="msg-card-list">
  @forelse ($messages as $msg)
    @php
      $words = explode(' ', trim($msg->name));
      $initials = count($words) >= 2 
        ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1)) 
        : strtoupper(substr($msg->name, 0, 2));
    @endphp

    <div class="msg-item {{ !$msg->is_read ? 'unread' : '' }}">
      <!-- Avatar Initials -->
      <div class="avatar-circle {{ $msg->is_read ? 'read' : '' }}">
        {{ $initials }}
      </div>

      <!-- Main Info & Message Preview -->
      <div class="msg-main-info">
        <div class="msg-header-row">
          <span class="sender-name">{{ $msg->name }}</span>
          <span class="sender-email">• {{ $msg->email }}</span>
          
          <span class="subject-tag">
            📌 {{ $msg->subject ?: 'Pesan Kontak' }}
          </span>

          @if (!$msg->is_read)
            <span style="background: #ef4444; color: #fff; font-size: 10px; font-weight: 800; padding: 2px 8px; border-radius: 10px;">BARU</span>
          @endif

          @if ($msg->replied_at)
            <span style="background: rgba(16, 185, 129, 0.2); color: #34d399; font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 10px;">DIBALAS</span>
          @endif
        </div>

        <div class="msg-snippet">
          {{ $msg->message }}
        </div>
      </div>

      <!-- Date & Actions -->
      <div>
        <div class="date-time">
          🕒 {{ $msg->created_at->diffForHumans() }}<br>
          <span style="font-size: 10.5px; opacity: 0.7;">{{ $msg->created_at->format('d M Y, H:i') }}</span>
        </div>

        <div class="msg-actions">
          <a href="{{ route('admin.contact.show', $msg->id) }}" class="btn btn-gold" style="padding: 6px 14px; font-size: 12px;">
            👁️ Rincian
          </a>

          <form action="{{ route('admin.contact.toggle_read', $msg->id) }}" method="POST" style="margin: 0;">
            @csrf
            <button type="submit" class="btn btn-outline" style="padding: 6px 10px; font-size: 12px;" title="{{ $msg->is_read ? 'Tandai Belum Dibaca' : 'Tandai Sudah Dibaca' }}">
              {{ $msg->is_read ? '✉️' : '✓' }}
            </button>
          </form>

          <form action="{{ route('admin.contact.destroy', $msg->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesan dari {{ $msg->name }}?');" style="margin: 0;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline" style="padding: 6px 10px; font-size: 12px; color: var(--danger); border-color: rgba(239, 68, 68, 0.3);" title="Hapus">
              🗑️
            </button>
          </form>
        </div>
      </div>
    </div>
  @empty
    <div style="background: var(--panel-bg); border: 1px solid var(--panel-border); border-radius: 12px; padding: 60px 20px; text-align: center;">
      <div style="font-size: 44px; margin-bottom: 12px;">🎉</div>
      @if (request('status') === 'unread')
        <h3 style="font-size: 18px; font-weight: 600; color: #fff; margin-bottom: 6px;">Tidak ada pesan baru yang belum dibaca</h3>
        <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 20px;">Semua pesan masuk di Concierge & Kontak telah Anda baca dan tindak lanjuti.</p>
        <a href="{{ route('admin.contact.index') }}" class="btn btn-gold">Lihat Semua Pesan</a>
      @else
        <h3 style="font-size: 18px; font-weight: 600; color: #fff; margin-bottom: 6px;">Belum Ada Pesan Terdaftar</h3>
        <p style="font-size: 13px; color: var(--text-muted);">Pesan dari formulir Kontak & Concierge di website akan otomatis muncul di sini.</p>
      @endif
    </div>
  @endforelse
</div>

@if($messages->hasPages())
  <div style="margin-top: 24px; display: flex; justify-content: center;">
    {{ $messages->appends(request()->query())->links() }}
  </div>
@endif
@endsection
