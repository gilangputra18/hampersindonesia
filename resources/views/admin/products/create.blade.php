@extends('layouts.admin')

@section('title', 'Tambah Produk Baru')
@section('page_title', 'Tambah Produk Baru')

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

  <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="form-group">
      <label for="name">Nama Produk *</label>
      <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" placeholder="Contoh: Red Velvet Cake" required>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label for="category_id">Kategori *</label>
        <select name="category_id" id="category_id" class="form-control" required>
          <option value="">-- Pilih Kategori --</option>
          @foreach ($categories as $cat)
            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
              {{ $cat->title }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="form-group">
        <label for="price">Harga (Rp) *</label>
        <input type="number" name="price" id="price" class="form-control" value="{{ old('price') }}" placeholder="250000" min="0" required>
      </div>
    </div>

    <!-- Additional Attributes: Type, Flavor, Size, Availability -->
    <div class="form-row">
      <div class="form-group">
        <label for="type">Tipe Produk (Type) ▾</label>
        <input type="text" name="type" id="type" class="form-control" list="type-options-create" value="{{ old('type') }}" placeholder="Pilih dari daftar atau ketik sendiri...">
        <datalist id="type-options-create">
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
        <input type="text" name="flavor" id="flavor" class="form-control" list="flavor-options-create" value="{{ old('flavor') }}" placeholder="Pilih dari daftar atau ketik sendiri...">
        <datalist id="flavor-options-create">
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
        <input type="text" name="size" id="size" class="form-control" list="size-options-create" value="{{ old('size') }}" placeholder="Contoh: Single Piece, Whole 18cm, Box of 6">
        <datalist id="size-options-create">
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
          <option value="in_stock" {{ old('availability') == 'in_stock' ? 'selected' : '' }}>In Stock (Tersedia)</option>
          <option value="pre_order" {{ old('availability') == 'pre_order' ? 'selected' : '' }}>Pre-Order</option>
          <option value="out_of_stock" {{ old('availability') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock (Habis)</option>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="image_file">Upload Foto Produk (Opsional - dari Komputer)</label>
      <input type="file" name="image_file" id="image_file" class="form-control" accept="image/*">
      <div style="font-size: 12px; color: var(--text-muted); margin-top: 6px;">Format yang didukung: JPG, PNG, WEBP (Maks 4MB)</div>
    </div>

    <div class="form-group">
      <label for="image_name">Atau Gunakan Nama File Gambar yang Ada di Folder public/images</label>
      <input type="text" name="image_name" id="image_name" class="form-control" value="{{ old('image_name') }}" placeholder="contoh: cat-cakes.jpg atau red-velvet.jpg">
    </div>

    <div class="form-group">
      <label for="description">🎁 Rincian Isi Hampers / Apa Saja yang Didapat (Deskripsi Produk)</label>
      <textarea name="description" id="description" class="form-control" rows="5" placeholder="Tuliskan rincian item per baris, contoh:
• 1 Jar Nastar Pineapple Jam (450g)
• 1 Jar Kaastengel Edam Cheese (400g)
• Kartu Ucapan Eksklusif & Hardbox Royal Emerald">{{ old('description') }}</textarea>
      <div style="font-size: 12px; color: var(--text-muted); margin-top: 6px;">
        💡 <strong>Petunjuk:</strong> Tulis 1 item per baris. Setiap baris otomatis ditampilkan di bagian modal & rincian isi hampers pembeli.
      </div>
    </div>

    <div class="form-row" style="margin-bottom: 24px;">
      <label class="checkbox-group">
        <input type="checkbox" name="is_best_seller" value="1" {{ old('is_best_seller') ? 'checked' : '' }}>
        <span>Tampilkan sebagai <strong>Best Seller</strong> di Beranda</span>
      </label>

      <label class="checkbox-group">
        <input type="checkbox" name="is_treat" value="1" {{ old('is_treat') ? 'checked' : '' }}>
        <span>Tampilkan di bagian <strong>Time for a Treat</strong></span>
      </label>
    </div>

    <div style="display: flex; gap: 12px;">
      <button type="submit" class="btn btn-gold">💾 Simpan Produk</button>
      <a href="{{ route('admin.products.index') }}" class="btn btn-outline">Batal</a>
    </div>
  </form>
</div>
@endsection
