import Alpine from 'alpinejs';

// Make Alpine available globally
window.Alpine = Alpine;

// Alpine Components
Alpine.data('searchBands', () => ({
  query: '',
  filters: {
    genre: '',
    location: '',
    priceMin: '',
    priceMax: '',
  },
  results: [],
  loading: false,

  init() {
    console.log('Search component initialized');
  },

  async search() {
    this.loading = true;
    try {
      const params = new URLSearchParams({
        q: this.query,
        ...this.filters
      });
      const response = await fetch(`/api/bands/search?${params}`);
      this.results = await response.json();
    } catch (error) {
      console.error('Search error:', error);
    } finally {
      this.loading = false;
    }
  }
}));

Alpine.data('bookingForm', () => ({
  formData: {
    bandId: '',
    eventDate: '',
    location: '',
    budget: '',
    eventType: '',
    message: ''
  },
  submitting: false,

  async submit() {
    this.submitting = true;
    try {
      const response = await fetch('/api/bookings', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(this.formData)
      });

      if (response.ok) {
        alert('Buchungsanfrage erfolgreich gesendet!');
        this.reset();
      }
    } catch (error) {
      console.error('Booking error:', error);
      alert('Es gab einen Fehler. Bitte versuchen Sie es erneut.');
    } finally {
      this.submitting = false;
    }
  },

  reset() {
    this.formData = {
      bandId: '',
      eventDate: '',
      location: '',
      budget: '',
      eventType: '',
      message: ''
    };
  }
}));

// Initialize Alpine
Alpine.start();

// Smooth scroll for anchor links
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        target.scrollIntoView({ behavior: 'smooth' });
      }
    });
  });
});
