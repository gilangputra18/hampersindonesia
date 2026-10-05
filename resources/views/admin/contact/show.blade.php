@extends('layouts.admin')

@section('title', 'Rincian Pesan Kontak #' . $message->id)
@section('page_title', 'Rincian Pesan Concierge')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
  <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
    <a href="{{ route('admin.contact.index') }}" class="btn btn-outline">
      ← Kembali ke Daftar Pesan
    </a>
    
    <div style="display: flex; gap: 10px;">
      <form action="{{ route('admin.contact.toggle_read', $message->id) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-outline">
          {{ $message->is_read ? '✉️ Tandai Belum Dibaca' : '✅ Tandai Sudah Dibaca' }}
        </button>
      </form>

      <form action="{{ route('admin.contact.destroy', $message->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesan ini?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-outline" style="color: var(--danger); border-color: rgba(239, 68, 68, 0.4);">
          🗑️ Hapus Pesan
        </button>
      </form>
    </div>
  </div>

  @if (session('success'))
    <div style="background: rgba(16, 185, 129, 0.15); border: 1px solid #10b981; color: #34d399; padding: 14px 20px; border-radius: 8px; margin-bottom: 24px; font-size: 14px;">
      ✅ {{ session('success') }}
    </div>
  @endif

  <!-- Message Detail Card -->
  <div class="panel" style="padding: 32px; border-top: 3px solid var(--accent-gold);">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; padding-bottom: 20px; border-bottom: 1px solid var(--panel-border);">
      <div>
        <h2 style="font-size: 22px; color: #fff; font-weight: 600; margin-bottom: 6px;">{{ $message->name }}</h2>
        <div style="font-size: 14px; color: var(--accent-gold);">
          ✉️ <a href="mailto:{{ $message->email }}" style="color: inherit; text-decoration: underline;">{{ $message->email }}</a>
        </div>
      </div>

      <div style="text-align: right;">
        <div style="font-size: 12px; color: var(--text-muted);">Diterima Pada:</div>
        <div style="font-weight: 600; font-size: 13px; color: #cbd5e1;">{{ $message->created_at->format('d M Y, H:i') }} WIB</div>
        <div style="margin-top: 8px;">
          @if ($message->replied_at)
            <span style="background: rgba(16, 185, 129, 0.2); color: #34d399; font-size: 11px; padding: 4px 10px; border-radius: 12px; font-weight: 600;">
              ✅ Dibalas pada {{ $message->replied_at->format('d M Y H:i') }}
            </span>
          @else
            <span style="background: rgba(234, 179, 8, 0.2); color: #fde047; font-size: 11px; padding: 4px 10px; border-radius: 12px; font-weight: 600;">
              ⏳ Menunggu Respon Concierge
            </span>
          @endif
        </div>
      </div>
    </div>

    <div style="margin-bottom: 24px;">
      <label style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); display: block; margin-bottom: 6px;">
        📌 Subjek Pesan:
      </label>
      <div style="font-size: 16px; font-weight: 600; color: #f8fafc;">
        {{ $message->subject ?: 'Pesan Kontak & Concierge' }}
      </div>
    </div>

    <div style="margin-bottom: 32px;">
      <label style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); display: block; margin-bottom: 8px;">
        💬 Isi Pesan Pelanggan:
      </label>
      <div style="background: rgba(15, 23, 42, 0.7); border: 1px solid var(--panel-border); border-radius: 8px; padding: 20px; font-size: 15px; line-height: 1.8; color: #f1f5f9; white-space: pre-wrap;">
{{ $message->message }}
      </div>
    </div>

    <!-- Quick Action Response Box -->
    <div style="background: rgba(217, 119, 6, 0.08); border: 1px solid rgba(217, 119, 6, 0.3); border-radius: 10px; padding: 24px;">
      <h4 style="font-size: 15px; color: var(--accent-gold); margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
        🚀 Respon Cepat Staf Butik & Concierge
      </h4>
      <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 16px;">
        Anda dapat langsung membalas pelanggan melalui Email resmi atau menandai status pesan ini setelah memberikan pelayanan.
      </p>

      <div style="display: flex; gap: 12px; flex-wrap: wrap;">
        <a href="mailto:{{ $message->email }}?subject={{ urlencode('Re: ' . ($message->subject ?: 'Pesan Kontak Pusat Hampers Indonesia')) }}&body={{ urlencode('Halo ' . $message->name . ",\n\nTerima kasih telah menghubungi Pusat Hampers Indonesia Concierge.\n\n") }}" class="btn btn-gold" target="_blank">
          ✉️ Balas via Email ({{ $message->email }})
        </a>

        @if (!$message->replied_at)
          <form action="{{ route('admin.contact.reply', $message->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline" style="border-color: #10b981; color: #34d399;">
              ✅ Tandai Sudah Dibalas
            </button>
          </form>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
