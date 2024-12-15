document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("dish-modal");
    const modalContent = document.getElementById("dish-details");
    const closeModal = document.querySelector(".modal .close");
    const dishCards = document.querySelectorAll(".dish-card");

    // Event listener untuk setiap kartu dish
    dishCards.forEach(card => {
        card.addEventListener("click", () => {
            const dishKey = card.dataset.dish; // Ambil key dari atribut data
            const dish = dishes[dishKey]; // Ambil data dish dari objek dishes

            if (dish) {
                // Isi konten modal dengan detail dish
                modalContent.innerHTML = `
                    <img src="${dish.image}" alt="${dish.name}">
                    <h2>${dish.name}</h2>
                    <p>Asal: ${dish.origin}</p>
                    <p>Waktu Masak: ${dish.cooking_time}</p>
                    <p>Tingkat Kesulitan: ${dish.difficulty}</p>
                    <p>Kalori: ${dish.calories}</p>
                    <h3>Bahan-bahan:</h3>
                    <ul>
                        ${dish.ingredients.map(ingredient => `<li>${ingredient}</li>`).join('')}
                    </ul>
                    <p>${dish.description}</p>
                `;

                // Tampilkan modal
                modal.classList.add("show");
            }
        });
    });

    // Tutup modal saat tombol close ditekan
    closeModal.addEventListener("click", () => {
        modal.classList.remove("show");
    });

    // Tutup modal saat klik di luar area modal-content
    window.addEventListener("click", (e) => {
        if (e.target === modal) {
            modal.classList.remove("show");
        }
    });
});


// Navigation Initialization
function initializeNavigation() {
    // Smooth scroll for navigation and explore button
    document.querySelectorAll('nav a, .explore-button').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            document.querySelector(targetId).scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        });
    });
}

function initializeModal() {
    const modal = document.getElementById('dish-modal');
    const dishCards = document.querySelectorAll('.dish-card');
    const closeBtn = document.querySelector('.close');
    const modalContent = document.querySelector('.modal-content');
  
    // Prevent modal content click from closing modal
    modalContent.addEventListener('click', function(e) {
      e.stopPropagation();
    });
  
    // Initialize dish cards click handlers
    dishCards.forEach(card => {
      card.addEventListener('click', function() {
        console.log("Clicked dish:", this.dataset.dish); // Add this line for debugging
        openModal(this.dataset.dish);
      });
    });
  
    // Close modal handlers
    closeBtn.addEventListener('click', closeModal);
    modal.addEventListener('click', closeModal);
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') closeModal();
    });
  }

// Modal Open Function
function openModal(dishId) {
    const modal = document.getElementById('dish-modal');
    const dish = dishes[dishId]; // Mengambil data hidangan dari variabel dishes
    
    const modalContent = `
        <div class="dish-detail">
            <img src="${dish.image}" alt="${dish.name}" loading="lazy">
            <div class="dish-info-container">
                <h2>${dish.name}</h2>
                <h3>Asal: ${dish.origin}</h3>
                <div class="dish-metadata">
                    <span><i class="far fa-clock"></i> ${dish.cooking_time}</span>
                    <span><i class="fas fa-signal"></i> ${dish.difficulty}</span>
                    <span><i class="fas fa-fire"></i> ${dish.calories}</span>
                </div>
                <div class="dish-description">
                    <h3>Deskripsi:</h3>
                    <p>${dish.description}</p>
                </div>
                <div class="dish-ingredients">
                    <h3>Bahan-bahan:</h3>
                    <ul>
                        ${dish.ingredients.map(ingredient => `<li>${ingredient}</li>`).join('')}
                    </ul>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('dish-details').innerHTML = modalContent;
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
    
    setTimeout(() => {
        document.querySelector('.dish-detail').classList.add('show');
    }, 10);
}

// Modal Close Function
function closeModal() {
    const modal = document.getElementById('dish-modal');
    document.querySelector('.dish-detail').classList.remove('show');
    
    setTimeout(() => {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto'; // Re-enable scrolling
    }, 300); // Match this with your CSS transition time
}

// Scroll Effects
function initializeScrollEffects() {
    let prevScrollPos = window.pageYOffset;
    let navTimeout;
    const navbar = document.querySelector('nav');
    
    window.addEventListener('scroll', function() {
        const currentScrollPos = window.pageYOffset;
        
        // Clear previous timeout
        clearTimeout(navTimeout);
        
        // Navbar hide/show logic
        if (prevScrollPos > currentScrollPos) {
            navbar.style.top = '0';
        } else {
            navbar.style.top = '-100px';
        }
        
        // Background opacity logic
        const scrollPercent = Math.min(currentScrollPos / 500, 1);
        navbar.style.backgroundColor = `rgba(255, 255, 255, ${0.8 + (scrollPercent * 0.2)})`;
        
        prevScrollPos = currentScrollPos;
        
        // Show navbar after user stops scrolling
        navTimeout = setTimeout(() => {
            navbar.style.top = '0';
        }, 1000);
    });
}

// Animations
function initializeAnimations() {
    // Add animation to dish cards on scroll
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1
    });

    document.querySelectorAll('.dish-card').forEach(card => {
        observer.observe(card);
    });

    // Add hover animations
    document.querySelectorAll('.dish-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.classList.add('hover');
        });
        
        card.addEventListener('mouseleave', function() {
            this.classList.remove('hover');
        });
    });
}

// Utility Functions
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Error Handling
window.addEventListener('error', function(e) {
    console.error('An error occurred:', e.error);
    // You could add more sophisticated error handling here
});

// Add loading state handlers
window.addEventListener('load', function() {
    document.body.classList.add('loaded');
});