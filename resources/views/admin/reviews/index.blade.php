@extends('layouts.admin')

@section('title', 'Kelola Ulasan Produk')
@section('page_title', 'Ulasan & Rating Produk')

@section('content')
<style>
  .review-card { background:var(--panel-bg);border:1px solid var(--panel-border);border-radius:12px;padding:24px;margin-bottom:28px;box-shadow:0 4px 15px rgba(0,0,0,0.2); }
  .star-display { color:#f59e0b;font-size:18px;letter-spacing:2px; }
  .star-empty   { color:#334155;font-size:18px; }
  .badge-approved  { background:#dcfce7;color:#166534;padding:3px 10px;border-radius:12px;font-size:11px;font-weight:700; }
  .badge-pending   { background:#fef3c7;color:#92400e;padding:3px 10px;border-radius:12px;font-size:11px;font-weight:700; }
  .badge-featured  { background:rgba(245,158,11,0.2);color:#fbbf24;padding:3px 10px;border-radius:12px;font-size:11px;font-weight:700; }
  .filter-bar  { display:flex;gap:12px;flex-wrap:wrap;margin-bottom:20px;align-items:center; }
  .filter-select { background:var(--panel-bg);color:var(--text-main);border:1px solid var(--panel-border);border-radius:6px;padding:8px 12px;font-size:12px; }
  .review-item { border:1px solid var(--panel-border);border-radius:10px;padding:18px;margin-bottom:16px;position:relative; }
  .review-item.pending-bg { border-left:4px solid #f59e0b; }
  .review-item.approved-bg { border-left:4px solid #10b981; }
</style>

@if(session('success'))
  <div style="background:rgba(16,185,129,0.15);border:1px solid #10b981;color:#6ee7b7;padding:12px 18px;border-radius:8px;margin-bottom:20px;font-size:13px;font-weight:600;">
    ✅ {{ session('success') }}
  </div>
@endif

{{-- Stats Bar --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:16px;margin-bottom:24px;">
  <div style="background:var(--panel-bg);border:1px solid var(--panel-border);border-radius:10px;padding:16px 20px;">
    <div style="font-size:24px;font-weight:700;color:#fff;">{{ $reviews->total() }}</div>
    <div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;">Total Ulasan</div>
  </div>
  <div style="background:var(--panel-bg);border:1px solid #f59e0b;border-radius:10px;padding:16px 20px;">
    <div style="font-size:24px;font-weight:700;color:#fbbf24;">{{ $pendingCount }}</div>
    <div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;">Menunggu Moderasi</div>
  </div>
</div>

{{-- Filter --}}
<form method="GET" class="filter-bar">
  <select name="status" class="filter-select" onchange="this.form.submit()">
    <option value="">Semua Status</option>
    <option value="pending"  {{ request('status')=='pending'  ?'selected':'' }}>⏳ Menunggu Moderasi</option>
    <option value="approved" {{ request('status')=='approved' ?'selected':'' }}>✅ Sudah Disetujui</option>
  </select>
  <select name="rating" class="filter-select" onchange="this.form.submit()">
    <option value="">Semua Rating</option>
    @for($i=5;$i>=1;$i--)
      <option value="{{ $i }}" {{ request('rating')==$i?'selected':'' }}>{{ str_repeat('★',$i) }} {{ $i }} Bintang</option>
    @endfor
  </select>
  @if(request('status') || request('rating'))
    <a href="{{ route('admin.reviews.index') }}" style="font-size:12px;color:var(--text-muted);text-decoration:underline;">Reset Filter</a>
  @endif
</form>

<div class="review-card">
  @forelse($reviews as $r)
    <div class="review-item {{ $r->is_approved ? 'approved-bg' : 'pending-bg' }}">
      <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:10px;margin-bottom:12px;">
        {{-- Left: stars + reviewer --}}
        <div>
          <div style="margin-bottom:4px;">
            <span class="star-display">{{ str_repeat('★', $r->rating) }}</span>
            <span style="color:#334155;font-size:18px;">{{ str_repeat('★', 5-$r->rating) }}</span>
            <span style="font-size:12px;color:var(--text-muted);margin-left:8px;">{{ $r->rating }}/5</span>
          </div>
          <div style="font-size:13px;font-weight:700;color:#fff;">{{ $r->reviewer_name }}</div>
          @if($r->reviewer_email)
            <div style="font-size:11px;color:var(--text-muted);">{{ $r->reviewer_email }}</div>
          @endif
          <div style="font-size:11px;color:var(--text-muted);margin-top:2px;">
            🕐 {{ $r->created_at->format('d M Y, H:i') }}
            @if($r->product) &nbsp;·&nbsp; 📦 <a href="{{ route('admin.products.edit', $r->product->id) }}" style="color:#f59e0b;text-decoration:none;">{{ $r->product->name }}</a> @endif
            @if($r->user) &nbsp;·&nbsp; 👤 Member: {{ $r->user->name }} @endif
          </div>
        </div>
        {{-- Right: badges --}}
        <div style="display:flex;gap:6px;flex-wrap:wrap;align-items:center;">
          @if($r->is_approved) <span class="badge-approved">✅ Disetujui</span>
          @else                <span class="badge-pending">⏳ Pending</span> @endif
          @if($r->is_featured) <span class="badge-featured">⭐ Unggulan</span> @endif
        </div>
      </div>

      {{-- Body --}}
      @if($r->title) <div style="font-size:14px;font-weight:700;color:#fbbf24;margin-bottom:6px;">"{{ $r->title }}"</div> @endif
      @if($r->body)  <div style="font-size:13px;color:#cad8d1;line-height:1.6;">{{ $r->body }}</div> @endif

      {{-- Actions --}}
      <div style="display:flex;gap:10px;margin-top:14px;flex-wrap:wrap;">
        <form action="{{ route('admin.reviews.approve', $r->id) }}" method="POST" style="display:inline;">
          @csrf
          <button type="submit" class="btn {{ $r->is_approved ? 'btn-outline' : 'btn-primary' }}" style="padding:6px 14px;font-size:12px;">
            {{ $r->is_approved ? '⏸ Sembunyikan' : '✅ Setujui' }}
          </button>
        </form>
        <form action="{{ route('admin.reviews.featured', $r->id) }}" method="POST" style="display:inline;">
          @csrf
          <button type="submit" class="btn btn-outline" style="padding:6px 14px;font-size:12px;">
            {{ $r->is_featured ? '☆ Batalkan Unggulan' : '⭐ Jadikan Unggulan' }}
          </button>
        </form>
        <form action="{{ route('admin.reviews.destroy', $r->id) }}" method="POST" style="display:inline;"
          onsubmit="return confirm('Hapus ulasan dari {{ addslashes($r->reviewer_name) }}?')">
          @csrf @method('DELETE')
          <button type="submit" class="btn btn-danger" style="padding:6px 14px;font-size:12px;">🗑 Hapus</button>
        </form>
      </div>
    </div>
  @empty
    <div style="text-align:center;padding:60px;color:var(--text-muted);">
      <div style="font-size:48px;margin-bottom:12px;">⭐</div>
      <p>Belum ada ulasan yang masuk.</p>
    </div>
  @endforelse

  <div style="margin-top:20px;">{{ $reviews->links('partials.pagination') }}</div>
</div>
@endsection
