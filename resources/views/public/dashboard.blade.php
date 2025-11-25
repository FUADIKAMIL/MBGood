@extends('layouts.app')

@section('content')

<!-- HERO -->
<section id="home" class="py-5 bg-dark text-light text-center">
  <div class="container">
    <h1 class="display-4 fw-bold">MBG</h1>
    <p class="lead mb-4">
      Program Kerja Prabowo
    </p>
    <a href="#destinations" class="btn btn-primary btn-lg">
      Pelajari Lebih Lanjut
    </a>
  </div>
</section>

<section id="destinations" class="py-5">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold">Telah Tersebar di Seluruh Indonesia</h2>
      <p class="text-muted">Merata dari kota hingga pelosok</p>
    </div>

    <div class="row g-4">
      <div class="col-md-4">
        <div class="card h-100">
          <img src="src/kawah-ijen.jpg" class="card-img-top" alt="Kawah Ijen">
          <div class="card-body">
            <h5 class="card-title">Kawah Ijen</h5>
            <p>Experience the world-famous blue fire and stunning crater lake sunrise.</p>
            <a href="#" class="btn btn-outline-primary">Learn More</a>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card h-100">
          <img src="src/kawah-wurung.jpg" class="card-img-top" alt="Kawah Wurung">
          <div class="card-body">
            <h5 class="card-title">Kawah Wurung</h5>
            <p>Green savannas with volcanic scenery, great for hiking and stargazing.</p>
            <a href="#" class="btn btn-outline-primary">Learn More</a>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card h-100">
          <img src="src/tancak-kembar.jpg" class="card-img-top" alt="Air Terjun Tancak Kembar">
          <div class="card-body">
            <h5 class="card-title">Air Terjun Tancak Kembar</h5>
            <p>A hidden waterfall sanctuary surrounded by fresh air and forests.</p>
            <a href="#" class="btn btn-outline-primary">Learn More</a>
          </div>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- GALLERY -->
<section id="gallery" class="py-5 bg-white">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold">Gallery</h2>
      <p class="text-muted">Moments from our adventures</p>
    </div>

    <div class="row g-4">
      @foreach ([
        'gallery-1.jpeg',
        'gallery-2.jpg',
        'gallery-3.jpg',
        'gallery-4.webp',
        'gallery-5.jpg',
        'gallery-6.jpg',
      ] as $img)
      <div class="col-md-4">
        <img src="src/{{ $img }}" class="img-fluid rounded w-100" alt="Gallery">
      </div>
      @endforeach
    </div>
  </div>
</section>

<!-- BLOG -->
<section id="blog" class="py-5">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold">Travel Stories</h2>
      <p class="text-muted">Latest adventures and guides</p>
    </div>

    <div class="row g-4">
      <div class="col-md-6">
        <div class="border rounded p-4 h-100">
          <h5>Exploring Bondowoso's Hidden Gems</h5>
          <p>
            Waterfalls, mountains, and untouched beauty waiting to be explored.
          </p>
          <a href="#" class="link-primary text-decoration-none">Read Full Story</a>
        </div>
      </div>

      <div class="col-md-6">
        <div class="border rounded p-4 h-100">
          <h5>A Journey to Kawah Ijen</h5>
          <p>
            Blue flames and crater sunrise — an unforgettable volcanic experience.
          </p>
          <a href="#" class="link-primary text-decoration-none">Read Full Story</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CONTACT -->
<section id="contact" class="py-5 bg-white">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold">Get In Touch</h2>
      <p class="text-muted">We’d love to hear from you</p>
    </div>

    <form class="mx-auto" style="max-width: 600px;">
      <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" id="name" class="form-control" placeholder="Your name">
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" id="email" class="form-control" placeholder="your@email">
      </div>

      <div class="mb-3">
        <label for="message" class="form-label">Message</label>
        <textarea id="message" rows="4" class="form-control" placeholder="Tell us something..."></textarea>
      </div>

      <button type="submit" class="btn btn-primary w-100">Send Message</button>
    </form>
  </div>
</section>

@endsection