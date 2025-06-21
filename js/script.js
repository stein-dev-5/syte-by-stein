const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
const navLinks = document.querySelector('.nav-links');
        
mobileMenuBtn.addEventListener('click', () => {
    navLinks.classList.toggle('active');
    mobileMenuBtn.innerHTML = navLinks.classList.contains('active') ? 
        '<i class="fas fa-times"></i>' : '<i class="fas fa-bars"></i>';
});

document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        
        if (navLinks.classList.contains('active')) {
            navLinks.classList.remove('active');
            mobileMenuBtn.innerHTML = '<i class="fas fa-bars"></i>';
        }
        
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});

window.addEventListener('scroll', () => {
    const header = document.querySelector('header');
    if (window.scrollY > 10) {
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }

    const backToTop = document.querySelector('.back-to-top');
    if (window.scrollY > 300) {
        backToTop.classList.add('active');
    } else {
        backToTop.classList.remove('active');
    }
});

const mapTabs = document.querySelectorAll('.map-tab');
mapTabs.forEach(tab => {
    tab.addEventListener('click', () => {
        mapTabs.forEach(t => t.classList.remove('active'));
        
        tab.classList.add('active');
        
        document.querySelectorAll('.map-content').forEach(content => {
            content.classList.remove('active');
        });
        
        const tabId = tab.getAttribute('data-tab');
        document.getElementById(`${tabId}-content`).classList.add('active');
    });
});

const swiper = new Swiper('.swiper', {
    slidesPerView: 1,
    spaceBetween: 30,
    loop: true,
    autoplay: {
        delay: 5000,
        disableOnInteraction: false,
    },
    pagination: {
        el: '.swiper-pagination',
        clickable: true,
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    breakpoints: {
        768: {
            slidesPerView: 2,
        },
        992: {
            slidesPerView: 3,
        }
    }
});

document.querySelector('.scroll-down').addEventListener('click', () => {
    window.scrollBy({
        top: window.innerHeight - 80,
        behavior: 'smooth'
    });
});

document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);
    const messageDiv = document.getElementById('form-message');

    fetch(form.action, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        messageDiv.innerHTML = data.message;
        messageDiv.className = data.success ? 'success' : 'error';
        
        if (data.success) {
            form.reset();
            setTimeout(() => {
                messageDiv.style.opacity = '0';
            }, 5000);
        }
    })
    .catch(error => {
        messageDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> Произошла ошибка';
        messageDiv.className = 'error';
    });
});

const animateOnScroll = () => {
    const elements = document.querySelectorAll('.service-card, .map-item, .about-image, .stat-item');
    
    elements.forEach(element => {
        const elementPosition = element.getBoundingClientRect().top;
        const screenPosition = window.innerHeight / 1.2;
        
        if (elementPosition < screenPosition) {
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }
    });
};

document.querySelectorAll('.service-card, .map-item, .about-image, .stat-item').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(30px)';
    el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
});

window.addEventListener('scroll', animateOnScroll);
window.addEventListener('load', animateOnScroll);

