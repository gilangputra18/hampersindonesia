@extends('layouts.admin')

@section('title', 'Edit Produk - ' . $product->name)
@section('page_title', 'Edit Produk')

@section('content')
<div class="panel" style="max-width: 760px;">
  @if ($errors->any())
    <div class="alert alert-danger">
      <strong>Terjadi kesalahan input:</strong>
      <ul style="margin-left: 20px; margin-top: 8px;">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="form-group">
      <label for="name">Nama Produk *</label>
      <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $product->name) }}" required>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label for="category_id">Kategori *</label>
        <select name="category_id" id="category_id" class="form-control" required>
          @foreach ($categories as $cat)
            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
              {{ $cat->title }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="form-group">
        <label for="price">Harga (Rp) *</label>
        <input type="number" name="price" id="price" class="form-control" value="{{ old('price', $product->price) }}" min="0" required>
      </div>
    </div>

    <!-- Additional Attributes: Type, Flavor, Size, Availability -->
    <div class="form-row">
      <div class="form-group">
        <label for="type">Tipe Produk (Type) ▾</label>
        <input type="text" name="type" id="type" class="form-control" list="type-options" value="{{ old('type', $product->type) }}" placeholder="Pilih dari daftar atau ketik sendiri...">
        <datalist id="type-options">
          <option value="Gift Box">
          <option value="Luxury Gift Set">
          <option value="Whole Cake">
          <option value="Dry Cookies">
          <option value="Pastry">
          <option value="Artisan Bread">
          <option value="Savory Rice">
          <option value="Rice Snack">
          <option value="Sandwich">
          <option value="Wrap">
          <option value="Tart">
          <option value="Traditional Cookie">
          <option value="Savory Bite">
          <option value="Rice Wrap">
        </datalist>
      </div>

      <div class="form-group">
        <label for="flavor">Rasa (Flavors) ▾</label>
        <input type="text" name="flavor" id="flavor" class="form-control" list="flavor-options" value="{{ old('flavor', $product->flavor) }}" placeholder="Pilih dari daftar atau ketik sendiri...">
        <datalist id="flavor-options">
          <option value="Original">
          <option value="French Butter">
          <option value="Edam Cheese">
          <option value="Pineapple Jam">
          <option value="Chocolate">
          <option value="Cheese">
          <option value="Berry">
          <option value="Coffee Mascarpone">
          <option value="Chocolate Cherry">
          <option value="Lotus & Egg Yolk">
          <option value="Braised Beef">
          <option value="Chicken Mushroom">
          <option value="Shredded Chicken">
          <option value="Spicy Chicken">
          <option value="Assorted Premium">
          <option value="Tuna Mayo">
          <option value="Chicken Pesto">
          <option value="Beef Cheese">
        </datalist>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label for="size">Ukuran (Size) ▾</label>
        <input type="text" name="size" id="size" class="form-control" list="size-options" value="{{ old('size', $product->size) }}" placeholder="Contoh: Single Piece, Whole 18cm, Box of 6">
        <datalist id="size-options">
          <option value="Single Piece">
          <option value="Box of 4">
          <option value="Box of 6">
          <option value="Small Box">
          <option value="Medium Box">
          <option value="Large Box">
          <option value="Jar 350g">
          <option value="Jar 400g">
          <option value="Jar 450g">
          <option value="Whole 16cm">
          <option value="Whole 18cm">
          <option value="Whole 20cm">
          <option value="Loaf (500g)">
          <option value="Pack of 6">
          <option value="Set of 3">
          <option value="Portion (6 pcs)">
        </datalist>
      </div>

      <div class="form-group">
        <label for="availability">Ketersediaan (Availability) *</label>
        <select name="availability" id="availability" class="form-control" required>
          <option value="in_stock" {{ old('availability', $product->availability) == 'in_stock' ? 'selected' : '' }}>In Stock (Tersedia)</option>
          <option value="pre_order" {{ old('availability', $product->availability) == 'pre_order' ? 'selected' : '' }}>Pre-Order</option>
          <option value="out_of_stock" {{ old('availability', $product->availability) == 'out_of_stock' ? 'selected' : '' }}>Out of Stock (Habis)</option>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label>Foto Produk saat Ini</label>
      <div style="display: flex; align-items: center; gap: 16px; margin-top: 8px;">
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; border: 1px solid var(--panel-border);">
        <div style="font-size: 13px; color: var(--text-muted);">
          Nama file: <code>{{ $product->image ?? 'Default slug' }}</code>
        </div>
      </div>
    </div>

    <div class="form-group">
      <label for="image_file">Ganti Foto Produk (Upload Baru dari Komputer)</label>
      <input type="file" name="image_file" id="image_file" class="form-control" accept="image/*">
    </div>

    <div class="form-group">
      <label for="image_name">Atau Ubah Nama File Gambar Manual</label>
      <input type="text" name="image_name" id="image_name" class="form-control" value="{{ old('image_name', $product->image) }}">
    </div>

    <div class="form-group">
      <label for="description">🎁 Rincian Isi Hampers / Apa Saja yang Didapat (Deskripsi Produk)</label>
      <textarea name="description" id="description" class="form-control" rows="5" placeholder="Tuliskan rincian item per baris, contoh:
• 1 Jar Nastar Pineapple Jam (450g)
• 1 Jar Kaastengel Edam Cheese (400g)
• Kartu Ucapan Eksklusif & Hardbox Royal Emerald">{{ old('description', $product->description) }}</textarea>
      <div style="font-size: 12px; color: var(--text-muted); margin-top: 6px;">
        💡 <strong>Petunjuk:</strong> Tulis 1 item per baris. Setiap baris otomatis ditampilkan di bagian modal & rincian isi hampers pembeli.
      </div>
    </div>

    <div class="form-row" style="margin-bottom: 24px;">
      <label class="checkbox-group">
        <input type="checkbox" name="is_best_seller" value="1" {{ old('is_best_seller', $product->is_best_seller) ? 'checked' : '' }}>
        <span>Tampilkan sebagai <strong>Best Seller</strong> di Beranda</span>
      </label>

      <label class="checkbox-group">
        <input type="checkbox" name="is_treat" value="1" {{ old('is_treat', $product->is_treat) ? 'checked' : '' }}>
        <span>Tampilkan di bagian <strong>Time for a Treat</strong></span>
      </label>
    </div>

    <div style="display: flex; gap: 12px;">
      <button type="submit" class="btn btn-gold">💾 Perbarui Produk</button>
      <a href="{{ route('admin.products.index') }}" class="btn btn-outline">Batal</a>
    </div>
  </form>
</div>
@endsection
