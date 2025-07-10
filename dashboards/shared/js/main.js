// Foz da Cova Dashboard System - Main JavaScript

// Utility Functions
const Utils = {
  // Format currency
  formatCurrency(amount) {
    return new Intl.NumberFormat('pt-PT', {
      style: 'currency',
      currency: 'EUR'
    }).format(amount);
  },

  // Format date
  formatDate(date) {
    return new Intl.DateTimeFormat('pt-PT', {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    }).format(new Date(date));
  },

  // Format date short
  formatDateShort(date) {
    return new Intl.DateTimeFormat('pt-PT', {
      month: 'short',
      day: 'numeric'
    }).format(new Date(date));
  },

  // Get time ago
  timeAgo(date) {
    const now = new Date();
    const past = new Date(date);
    const diffInSeconds = Math.floor((now - past) / 1000);
    
    if (diffInSeconds < 60) return 'just now';
    if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)}m ago`;
    if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)}h ago`;
    if (diffInSeconds < 2592000) return `${Math.floor(diffInSeconds / 86400)}d ago`;
    return `${Math.floor(diffInSeconds / 2592000)}mo ago`;
  },

  // Show notification
  showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
      <div class="notification-content">
        <span class="notification-message">${message}</span>
        <button class="notification-close">&times;</button>
      </div>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
      notification.remove();
    }, 5000);
    
    // Close button
    notification.querySelector('.notification-close').addEventListener('click', () => {
      notification.remove();
    });
  },

  // Show loading state
  showLoading(element) {
    element.classList.add('loading');
    element.innerHTML = '<div class="loading-spinner"></div>';
  },

  // Hide loading state
  hideLoading(element, content) {
    element.classList.remove('loading');
    element.innerHTML = content;
  },

  // Validate form
  validateForm(form) {
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;
    
    inputs.forEach(input => {
      if (!input.value.trim()) {
        input.classList.add('error');
        isValid = false;
      } else {
        input.classList.remove('error');
      }
    });
    
    return isValid;
  },

  // Debounce function
  debounce(func, wait) {
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
};

// Navigation System
const Navigation = {
  currentPage: null,
  
  init() {
    this.setupNavigation();
    this.setupMobileMenu();
    this.highlightCurrentPage();
  },
  
  setupNavigation() {
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
      link.addEventListener('click', (e) => {
        e.preventDefault();
        const href = link.getAttribute('href');
        this.navigateTo(href);
      });
    });
  },
  
  setupMobileMenu() {
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const sidebar = document.querySelector('.sidebar');
    
    if (mobileMenuToggle && sidebar) {
      mobileMenuToggle.addEventListener('click', () => {
        sidebar.classList.toggle('mobile-open');
      });
    }
  },
  
  highlightCurrentPage() {
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
      const href = link.getAttribute('href');
      if (href === currentPath || (currentPath === '/' && href === '/admin')) {
        link.classList.add('active');
      }
    });
  },
  
  navigateTo(path) {
    // For now, just update the URL and reload
    // In a real app, this would use client-side routing
    window.location.href = path;
  }
};

// Dashboard Data Management
const DashboardData = {
  // Mock data for demonstration
  mockData: {
    stats: {
      activeProjects: 12,
      totalResidents: 8,
      monthlyExpenses: 2450,
      pendingApplications: 3
    },
    
    recentActivities: [
      {
        id: 1,
        type: 'project',
        title: 'Garden Path Completed',
        description: 'The new garden path has been finished',
        timestamp: new Date(Date.now() - 2 * 60 * 60 * 1000), // 2 hours ago
        user: 'Maria Silva'
      },
      {
        id: 2,
        type: 'application',
        title: 'New Application Received',
        description: 'João Santos applied for WWOOFer position',
        timestamp: new Date(Date.now() - 4 * 60 * 60 * 1000), // 4 hours ago
        user: 'System'
      },
      {
        id: 3,
        type: 'expense',
        title: 'Tools Purchased',
        description: 'New gardening tools - €125',
        timestamp: new Date(Date.now() - 6 * 60 * 60 * 1000), // 6 hours ago
        user: 'Admin'
      }
    ],
    
    currentResidents: [
      {
        id: 1,
        name: 'Maria Silva',
        role: 'Stewart',
        phone: '+351 912 345 678',
        email: 'maria@fozdacova.pt',
        arrivalDate: '2024-01-15',
        status: 'active'
      },
      {
        id: 2,
        name: 'João Santos',
        role: 'WWOOFer',
        phone: '+351 923 456 789',
        email: 'joao@email.com',
        arrivalDate: '2024-02-01',
        status: 'active'
      },
      {
        id: 3,
        name: 'Ana Costa',
        role: 'WWOOFer',
        phone: '+351 934 567 890',
        email: 'ana@email.com',
        arrivalDate: '2024-02-10',
        status: 'active'
      }
    ],
    
    activeProjects: [
      {
        id: 1,
        title: 'Water System Maintenance',
        category: 'maintenance',
        status: 'active',
        coordinator: 'Maria Silva',
        participants: 3,
        startDate: '2024-02-01',
        endDate: '2024-02-15',
        progress: 75
      },
      {
        id: 2,
        title: 'Spring Garden Planting',
        category: 'gardening',
        status: 'active',
        coordinator: 'João Santos',
        participants: 4,
        startDate: '2024-02-05',
        endDate: '2024-02-20',
        progress: 45
      },
      {
        id: 3,
        title: 'Community Kitchen Renovation',
        category: 'construction',
        status: 'active',
        coordinator: 'Maria Silva',
        participants: 2,
        startDate: '2024-01-20',
        endDate: '2024-03-01',
        progress: 30
      }
    ],
    
    upcomingCalls: [
      {
        id: 1,
        title: 'Weekly Community Call',
        date: new Date(Date.now() + 2 * 24 * 60 * 60 * 1000), // 2 days from now
        time: '17:00',
        participants: 8,
        type: 'community'
      },
      {
        id: 2,
        title: 'Project Planning Meeting',
        date: new Date(Date.now() + 5 * 24 * 60 * 60 * 1000), // 5 days from now
        time: '14:00',
        participants: 4,
        type: 'project'
      }
    ]
  },
  
  // Get dashboard stats
  getStats() {
    return this.mockData.stats;
  },
  
  // Get recent activities
  getRecentActivities() {
    return this.mockData.recentActivities;
  },
  
  // Get current residents
  getCurrentResidents() {
    return this.mockData.currentResidents;
  },
  
  // Get active projects
  getActiveProjects() {
    return this.mockData.activeProjects;
  },
  
  // Get upcoming calls
  getUpcomingCalls() {
    return this.mockData.upcomingCalls;
  }
};

// Chart Utilities (for future use)
const Charts = {
  // Simple progress bar
  createProgressBar(percentage, container) {
    const progressBar = document.createElement('div');
    progressBar.className = 'progress-bar';
    progressBar.innerHTML = `
      <div class="progress-fill" style="width: ${percentage}%"></div>
      <span class="progress-text">${percentage}%</span>
    `;
    container.appendChild(progressBar);
  },
  
  // Simple bar chart
  createBarChart(data, container) {
    const chart = document.createElement('div');
    chart.className = 'bar-chart';
    
    data.forEach(item => {
      const bar = document.createElement('div');
      bar.className = 'bar';
      bar.style.height = `${item.value}%`;
      bar.innerHTML = `<span class="bar-label">${item.label}</span>`;
      chart.appendChild(bar);
    });
    
    container.appendChild(chart);
  }
};

// Form Handling
const FormHandler = {
  init() {
    this.setupFormValidation();
    this.setupFormSubmissions();
  },
  
  setupFormValidation() {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
      form.addEventListener('submit', (e) => {
        if (!Utils.validateForm(form)) {
          e.preventDefault();
          Utils.showNotification('Please fill in all required fields', 'error');
        }
      });
    });
  },
  
  setupFormSubmissions() {
    const forms = document.querySelectorAll('form[data-ajax]');
    forms.forEach(form => {
      form.addEventListener('submit', (e) => {
        e.preventDefault();
        this.handleAjaxSubmit(form);
      });
    });
  },
  
  async handleAjaxSubmit(form) {
    const submitButton = form.querySelector('button[type="submit"]');
    const originalText = submitButton.textContent;
    
    // Show loading state
    Utils.showLoading(submitButton);
    
    try {
      // Simulate API call
      await new Promise(resolve => setTimeout(resolve, 1000));
      
      // Show success message
      Utils.showNotification('Form submitted successfully!', 'success');
      form.reset();
      
    } catch (error) {
      Utils.showNotification('Error submitting form. Please try again.', 'error');
    } finally {
      Utils.hideLoading(submitButton, originalText);
    }
  }
};

// Search and Filter
const SearchFilter = {
  init() {
    this.setupSearch();
    this.setupFilters();
  },
  
  setupSearch() {
    const searchInputs = document.querySelectorAll('.search-input');
    searchInputs.forEach(input => {
      input.addEventListener('input', Utils.debounce((e) => {
        this.performSearch(e.target.value);
      }, 300));
    });
  },
  
  setupFilters() {
    const filterSelects = document.querySelectorAll('.filter-select');
    filterSelects.forEach(select => {
      select.addEventListener('change', (e) => {
        this.applyFilter(e.target.value, e.target.dataset.filterType);
      });
    });
  },
  
  performSearch(query) {
    const searchableElements = document.querySelectorAll('[data-searchable]');
    
    searchableElements.forEach(element => {
      const text = element.textContent.toLowerCase();
      const matches = text.includes(query.toLowerCase());
      
      if (matches || query === '') {
        element.style.display = '';
      } else {
        element.style.display = 'none';
      }
    });
  },
  
  applyFilter(filterValue, filterType) {
    const filterableElements = document.querySelectorAll(`[data-${filterType}]`);
    
    filterableElements.forEach(element => {
      const elementValue = element.dataset[filterType];
      const matches = filterValue === 'all' || elementValue === filterValue;
      
      if (matches) {
        element.style.display = '';
      } else {
        element.style.display = 'none';
      }
    });
  }
};

// Initialize everything when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
  Navigation.init();
  FormHandler.init();
  SearchFilter.init();
  
  // Add fade-in animation to main content
  const mainContent = document.querySelector('.content-area');
  if (mainContent) {
    mainContent.classList.add('fade-in');
  }
});

// Export for use in other modules
window.FozDaCova = {
  Utils,
  Navigation,
  DashboardData,
  Charts,
  FormHandler,
  SearchFilter
}; 