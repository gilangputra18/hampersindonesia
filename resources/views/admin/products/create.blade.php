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
        <label for="type">Tipe Produk (Type)</label>
        <input type="text" name="type" id="type" class="form-control" value="{{ old('type') }}" placeholder="Contoh: Savory Rice, Whole Cake, Pastry">
      </div>

      <div class="form-group">
        <label for="flavor">Rasa (Flavors)</label>
        <input type="text" name="flavor" id="flavor" class="form-control" value="{{ old('flavor') }}" placeholder="Contoh: Chocolate, Cheese, Chicken">
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label for="size">Ukuran (Size)</label>
        <input type="text" name="size" id="size" class="form-control" value="{{ old('size') }}" placeholder="Contoh: Single Piece, Whole 18cm, Box of 6">
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
      <label for="description">Deskripsi Produk (Opsional)</label>
      <textarea name="description" id="description" class="form-control" rows="4" placeholder="Deskripsi singkat produk bakery...">{{ old('description') }}</textarea>
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
