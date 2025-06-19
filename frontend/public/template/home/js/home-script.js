// Hero Carousel
class HeroCarousel {
    constructor() {
        this.slides = document.querySelectorAll(".hero-slide");
        this.indicators = document.querySelectorAll(".indicator");
        this.prevBtn = document.querySelector(".prev-btn");
        this.nextBtn = document.querySelector(".next-btn");
        this.currentSlide = 0;
        this.slideInterval = null;

        this.init();
    }

    init() {
        // Event listeners
        this.prevBtn.addEventListener("click", () => this.prevSlide());
        this.nextBtn.addEventListener("click", () => this.nextSlide());

        this.indicators.forEach((indicator, index) => {
            indicator.addEventListener("click", () => this.goToSlide(index));
        });

        // Auto-play
        this.startAutoPlay();

        // Pause on hover
        const heroSection = document.querySelector(".hero-section");
        heroSection.addEventListener("mouseenter", () => this.stopAutoPlay());
        heroSection.addEventListener("mouseleave", () => this.startAutoPlay());
    }

    goToSlide(slideIndex) {
        // Remove active class from current slide and indicator
        this.slides[this.currentSlide].classList.remove("active");
        this.indicators[this.currentSlide].classList.remove("active");

        // Update current slide
        this.currentSlide = slideIndex;

        // Add active class to new slide and indicator
        this.slides[this.currentSlide].classList.add("active");
        this.indicators[this.currentSlide].classList.add("active");
    }

    nextSlide() {
        const nextIndex = (this.currentSlide + 1) % this.slides.length;
        this.goToSlide(nextIndex);
    }

    prevSlide() {
        const prevIndex =
            (this.currentSlide - 1 + this.slides.length) % this.slides.length;
        this.goToSlide(prevIndex);
    }

    startAutoPlay() {
        this.slideInterval = setInterval(() => {
            this.nextSlide();
        }, 5000);
    }

    stopAutoPlay() {
        if (this.slideInterval) {
            clearInterval(this.slideInterval);
        }
    }
}

// Dropdown Menu
class DropdownMenu {
    constructor() {
        this.dropdowns = document.querySelectorAll(".dropdown");
        this.init();
    }

    init() {
        this.dropdowns.forEach((dropdown) => {
            const toggle = dropdown.querySelector(".dropdown-toggle");

            toggle.addEventListener("click", (e) => {
                e.stopPropagation();
                this.toggleDropdown(dropdown);
            });
        });

        // Close dropdowns when clicking outside
        document.addEventListener("click", () => {
            this.closeAllDropdowns();
        });
    }

    toggleDropdown(dropdown) {
        const isActive = dropdown.classList.contains("active");

        // Close all dropdowns first
        this.closeAllDropdowns();

        // Toggle current dropdown
        if (!isActive) {
            dropdown.classList.add("active");
        }
    }

    closeAllDropdowns() {
        this.dropdowns.forEach((dropdown) => {
            dropdown.classList.remove("active");
        });
    }
}

// Product Interactions
class ProductInteractions {
    constructor() {
        this.favoriteButtons = document.querySelectorAll(".favorite-btn");
        this.buyButtons = document.querySelectorAll(".buy-btn");
        this.productCards = document.querySelectorAll(".product-card");

        this.init();
    }

    init() {
        // Favorite buttons
        this.favoriteButtons.forEach((btn) => {
            btn.addEventListener("click", (e) => {
                e.stopPropagation();
                this.toggleFavorite(btn);
            });
        });

        // Buy buttons
        this.buyButtons.forEach((btn) => {
            btn.addEventListener("click", (e) => {
                e.stopPropagation();
                this.handleBuy(btn);
            });
        });

        // Product cards
        this.productCards.forEach((card) => {
            card.addEventListener("click", () => {
                this.handleProductClick(card);
            });
        });
    }

    toggleFavorite(btn) {
        const icon = btn.querySelector("i");
        const isActive = btn.classList.contains("active");

        if (isActive) {
            btn.classList.remove("active");
            icon.className = "far fa-heart";
        } else {
            btn.classList.add("active");
            icon.className = "fas fa-heart";
        }

        // Add animation
        btn.style.transform = "scale(1.2)";
        setTimeout(() => {
            btn.style.transform = "scale(1)";
        }, 150);
    }

    handleProductClick(card) {
        // Navigate to product detail page
        // In Laravel, you would redirect to the product detail route
        console.log("Navigate to product detail");
    }

    updateCartCount() {
        const cartBadge = document.querySelector(".cart-badge");
        const currentCount = Number.parseInt(cartBadge.textContent);
        cartBadge.textContent = currentCount + 1;

        // Add pulse animation
        cartBadge.style.animation = "none";
        setTimeout(() => {
            cartBadge.style.animation = "pulse 2s infinite";
        }, 10);
    }

    showNotification(message) {
        // Create notification element
        const notification = document.createElement("div");
        notification.className = "notification";
        notification.textContent = message;
        notification.style.cssText = `
              position: fixed;
              top: 20px;
              right: 20px;
              background: #16a34a;
              color: white;
              padding: 1rem 1.5rem;
              border-radius: 0.5rem;
              box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
              z-index: 1000;
              transform: translateX(100%);
              transition: transform 0.3s ease;
          `;

        document.body.appendChild(notification);

        // Show notification
        setTimeout(() => {
            notification.style.transform = "translateX(0)";
        }, 100);

        // Hide notification after 3 seconds
        setTimeout(() => {
            notification.style.transform = "translateX(100%)";
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }
}

// Smooth Scrolling for anchor links
class SmoothScroll {
    constructor() {
        this.init();
    }

    init() {
        document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
            anchor.addEventListener("click", (e) => {
                e.preventDefault();
                const target = document.querySelector(
                    anchor.getAttribute("href")
                );
                if (target) {
                    target.scrollIntoView({
                        behavior: "smooth",
                        block: "start",
                    });
                }
            });
        });
    }
}

// Intersection Observer for animations
class ScrollAnimations {
    constructor() {
        this.observer = new IntersectionObserver(
            (entries) => this.handleIntersection(entries),
            {
                threshold: 0.1,
                rootMargin: "0px 0px -50px 0px",
            }
        );

        this.init();
    }

    init() {
        // Observe elements for animation
        const animatedElements = document.querySelectorAll(
            ".category-card, .product-card, .fresh-content, .farmer-content"
        );

        animatedElements.forEach((el) => {
            el.style.opacity = "0";
            el.style.transform = "translateY(30px)";
            el.style.transition = "opacity 0.6s ease, transform 0.6s ease";
            this.observer.observe(el);
        });
    }

    handleIntersection(entries) {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = "1";
                entry.target.style.transform = "translateY(0)";
                this.observer.unobserve(entry.target);
            }
        });
    }
}

// Initialize all components when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
    new HeroCarousel();
    new DropdownMenu();
    new ProductInteractions();
    new SmoothScroll();
    new ScrollAnimations();
});

// Search functionality
document.querySelector(".search-input").addEventListener("input", (e) => {
    const searchTerm = e.target.value.toLowerCase();
    // Implement search logic here
    // In Laravel, you would typically make an AJAX request to search endpoint
    console.log("Searching for:", searchTerm);
});

// Category card clicks
document.querySelectorAll(".category-card").forEach((card) => {
    card.addEventListener("click", () => {
        const categoryName = card.querySelector(".category-name").textContent;
        // Navigate to category page
        // In Laravel: window.location.href = `/category/${categorySlug}`;
        console.log("Navigate to category:", categoryName);
    });
});
