/* global React, ReactDOM */

(() => {
  const mountEl = document.getElementById("home-react");
  if (!mountEl) return;

  const products = Array.isArray(window.__HOME_PRODUCTS__) ? window.__HOME_PRODUCTS__ : [];
  const user = window.__HOME_USER__ || { loggedIn: false, isUser: false };

  const hasReact = typeof window.React !== "undefined" && typeof window.ReactDOM !== "undefined";
  if (!hasReact) {
    mountEl.innerHTML = '<div class="alert alert-danger">React failed to load. Please refresh the page.</div>';
    return;
  }

  function normalizeImagePath(src) {
    if (!src || typeof src !== "string") return "";
    // DB may store "./assets/..." but browser wants "assets/..."
    return src.startsWith("./") ? src.slice(2) : src;
  }

  function formatPrice(price) {
    const n = typeof price === "number" ? price : Number(price);
    if (!Number.isFinite(n)) return "";
    return n.toLocaleString(undefined, { minimumFractionDigits: 0, maximumFractionDigits: 2 });
  }

  function isInStock(p) {
    const qty = p && p.qty != null ? Number(p.qty) : null;
    if (qty == null || !Number.isFinite(qty)) return true; // treat unknown as available
    return qty > 0;
  }

  function HeroActions() {
    if (!user.loggedIn) {
      return (
        <div className="d-flex flex-wrap gap-2 justify-content-center justify-content-lg-start">
          <a href="./?page=login" className="btn btn-primary btn-lg rounded-3 px-4">
            Login
          </a>
          <a href="./?page=register" className="btn btn-outline-light btn-lg rounded-3 px-4">
            Register
          </a>
        </div>
      );
    }

    return (
      <div className="d-flex flex-wrap gap-2 justify-content-center justify-content-lg-start">
        <a href="./?page=dashboard" className="btn btn-primary btn-lg rounded-3 px-4">
          Go to Dashboard
        </a>
        {user.isUser ? (
          <a href="./?page=cart/home" className="btn btn-outline-light btn-lg rounded-3 px-4">
            Open Cart
          </a>
        ) : null}
      </div>
    );
  }

  function HomeCarousel() {
    const slides = [
      {
        src: "assets/images/home-banner-1.png",
        kicker: "ZANDO.HOME",
        title: "Your modern home essentials",
        cta: "SHOP NOW",
        href: "./?page=product/home",
      },
      {
        src: "assets/images/home-banner-2.png",
        kicker: "SUMMER '26",
        title: "Up to 80% Off — Summer's Here!",
        cta: "SHOP MORE",
        href: "./?page=product/home",
      },
      {
        src: "assets/images/home-banner-3.jpg",
        kicker: "NEW ARRIVALS",
        title: "Fresh picks for your store",
        cta: "Explore",
        href: "./?page=product/home",
      },
    ];

    return (
      <div className="d-flex justify-content-center">
        <div className="home-carousel-wrap">
          <div id="homeCarousel" className="carousel slide" data-bs-ride="carousel">
            <div className="carousel-indicators">
              {slides.map((_, idx) => (
                <button
                  key={String(idx)}
                  type="button"
                  data-bs-target="#homeCarousel"
                  data-bs-slide-to={String(idx)}
                  className={idx === 0 ? "active" : ""}
                  aria-current={idx === 0 ? "true" : "false"}
                  aria-label={`Slide ${idx + 1}`}
                />
              ))}
            </div>

            <div className="carousel-inner">
              {slides.map((s, idx) => (
                <div key={s.src} className={`carousel-item ${idx === 0 ? "active" : ""}`}>
                  <div className="home-slide">
                    <img src={s.src} alt={`Slide ${idx + 1}`} className="home-slide-img" />

                    <div className="home-slide-caption">
                      <div className="home-slide-card">
                        <div className="home-slide-brand">{s.kicker}</div>
                        <div className="home-slide-title">{s.title}</div>
                        <a className="btn btn-dark rounded-3 px-3 home-slide-btn" href={s.href}>
                          {s.cta}
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              ))}
            </div>

            <button className="carousel-control-prev" type="button" data-bs-target="#homeCarousel" data-bs-slide="prev">
              <span className="carousel-control-prev-icon" aria-hidden="true" />
              <span className="visually-hidden">Previous</span>
            </button>
            <button className="carousel-control-next" type="button" data-bs-target="#homeCarousel" data-bs-slide="next">
              <span className="carousel-control-next-icon" aria-hidden="true" />
              <span className="visually-hidden">Next</span>
            </button>
          </div>
        </div>
      </div>
    );
  }

  function ProductGrid() {
    const [query, setQuery] = React.useState("");

    const q = query.trim().toLowerCase();
    const filtered = q
      ? products.filter((p) => {
          const name = String(p?.name || "").toLowerCase();
          const des = String(p?.short_des || "").toLowerCase();
          return name.includes(q) || des.includes(q);
        })
      : products;

    if (!products.length) {
      return (
        <div className="alert alert-info mt-4">
          No products found.{" "}
          <a className="alert-link" href="./?page=product/create">
            Add a product
          </a>{" "}
          to get started.
        </div>
      );
    }

    return (
      <div className="mt-4">
        <div className="d-flex align-items-end justify-content-between mb-3">
          <div>
            <div className="text-muted small">Products</div>
            <h4 className="fw-bold mb-0">Featured products</h4>
          </div>
          <div className="d-flex align-items-center gap-2">
            <div className="home-search">
              <input
                className="form-control form-control-sm rounded-3"
                placeholder="Search products..."
                value={query}
                onChange={(e) => setQuery(e.target.value)}
                aria-label="Search products"
              />
            </div>
            <a className="btn btn-outline-secondary btn-sm rounded-3" href="./?page=product/home">
              View all
            </a>
          </div>
        </div>

        {q && !filtered.length ? (
          <div className="alert alert-light border rounded-4 mb-3">
            No results for <span className="fw-semibold">{query}</span>.
          </div>
        ) : null}

        <div className="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 g-3 g-md-4">
          {filtered.map((p) => {
            const img = normalizeImagePath(p.image) || "assets/images/slide-1.svg";
            const inStock = isInStock(p);
            const priceLabel = formatPrice(p.price);
            return (
            <div className="col" key={String(p.id_product)}>
              <div className="card h-100 shadow-sm border-0 home-product-card">
                <img
                  src={img}
                  alt={p.name || "Product"}
                  className="home-product-img"
                  loading="lazy"
                  onError={(e) => {
                    // fallback once
                    if (e?.currentTarget && e.currentTarget.getAttribute("data-fallback") !== "1") {
                      e.currentTarget.setAttribute("data-fallback", "1");
                      e.currentTarget.src = "assets/images/slide-1.svg";
                    }
                  }}
                />

                <div className="card-body">
                  <div className="d-flex align-items-start justify-content-between gap-2">
                    <div className="fw-semibold home-clamp-2">{p.name || "Unnamed"}</div>
                    {priceLabel ? (
                      <span className="badge text-bg-primary">{`$${priceLabel}`}</span>
                    ) : (
                      <span className="badge text-bg-secondary">N/A</span>
                    )}
                  </div>
                  <div className="text-muted small mt-2 home-clamp-2">{p.short_des || ""}</div>
                  <div className="mt-3 d-flex flex-wrap gap-2">
                    <span className={`badge ${inStock ? "text-bg-success" : "text-bg-danger"}`}>
                      {inStock ? "In stock" : "Out of stock"}
                    </span>
                    {p.qty != null && String(p.qty) !== "" ? (
                      <span className="badge text-bg-light border text-dark">{`Qty: ${p.qty}`}</span>
                    ) : null}
                  </div>
                </div>

                <div className="card-footer bg-white border-0 pt-0">
                  <div className="d-grid">
                    <a
                      role="button"
                      href={`./?page=cart/create&id=${encodeURIComponent(p.id_product)}`}
                      className={`btn rounded-3 ${inStock ? "btn-primary" : "btn-secondary disabled"}`}
                      aria-disabled={inStock ? "false" : "true"}
                      tabIndex={inStock ? 0 : -1}
                    >
                      Add to cart
                    </a>
                  </div>
                </div>
              </div>
            </div>
          )})}
        </div>
      </div>
    );
  }

  function HomeApp() {
    return (
      <div className="home-page">
        <div className="home-banner">
          <HomeCarousel />
        </div>

        <div className="home-products">
          <ProductGrid />
        </div>
      </div>
    );
  }

  const root = ReactDOM.createRoot ? ReactDOM.createRoot(mountEl) : null;
  if (root) root.render(<HomeApp />);
  else ReactDOM.render(<HomeApp />, mountEl);
})();

