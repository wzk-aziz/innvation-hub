/**
 * Modern Search Component
 * Enhanced search functionality with animations and UX improvements
 */
class ModernSearch {
    constructor(container) {
        this.container = typeof container === 'string' ? document.querySelector(container) : container;
        this.searchInput = this.container.querySelector('.modern-search-input');
        this.clearBtn = this.container.querySelector('.clear-search');
        this.filterToggle = this.container.querySelector('.filter-toggle-btn');
        this.filterContainer = this.container.querySelector('.filter-toggle');
        this.form = this.container.querySelector('.modern-search-form');
        
        this.init();
    }
    
    init() {
        this.setupClearButton();
        this.setupFilterToggle();
        this.setupPlaceholderAnimation();
        this.setupFormValidation();
        this.setupKeyboardShortcuts();
        this.setupAutoComplete();
    }
    
    setupClearButton() {
        if (!this.clearBtn || !this.searchInput) return;
        
        this.clearBtn.addEventListener('click', (e) => {
            e.preventDefault();
            this.clearSearch();
        });
        
        // Show/hide clear button based on input content
        this.searchInput.addEventListener('input', () => {
            this.updateClearButtonVisibility();
        });
        
        // Initial state
        this.updateClearButtonVisibility();
    }
    
    clearSearch() {
        this.searchInput.value = '';
        this.searchInput.focus();
        this.updateClearButtonVisibility();
        
        // Add a subtle animation
        this.searchInput.style.transform = 'scale(0.98)';
        setTimeout(() => {
            this.searchInput.style.transform = 'scale(1)';
        }, 100);
    }
    
    updateClearButtonVisibility() {
        if (!this.clearBtn) return;
        
        const hasValue = this.searchInput.value.trim().length > 0;
        this.clearBtn.style.opacity = hasValue ? '1' : '0';
        this.clearBtn.style.pointerEvents = hasValue ? 'auto' : 'none';
    }
    
    setupFilterToggle() {
        if (!this.filterToggle || !this.filterContainer) return;
        
        this.filterToggle.addEventListener('click', (e) => {
            e.preventDefault();
            this.toggleFilters();
        });
    }
    
    toggleFilters() {
        const isActive = this.filterContainer.classList.contains('active');
        
        if (isActive) {
            this.hideFilters();
        } else {
            this.showFilters();
        }
    }
    
    showFilters() {
        this.filterContainer.classList.add('active');
        const panel = this.container.querySelector('.filter-panel');
        if (panel) {
            panel.classList.add('slide-down');
            setTimeout(() => panel.classList.remove('slide-down'), 300);
        }
    }
    
    hideFilters() {
        this.filterContainer.classList.remove('active');
    }
    
    setupPlaceholderAnimation() {
        if (!this.searchInput) return;
        
        const originalPlaceholder = this.searchInput.placeholder;
        const context = this.getSearchContext();
        const placeholders = this.getContextualPlaceholders(context, originalPlaceholder);
        
        let currentIndex = 0;
        let animationInterval;
        
        const startAnimation = () => {
            animationInterval = setInterval(() => {
                if (this.searchInput === document.activeElement) return;
                
                currentIndex = (currentIndex + 1) % placeholders.length;
                this.animatePlaceholderChange(placeholders[currentIndex]);
            }, 3000);
        };
        
        const stopAnimation = () => {
            if (animationInterval) {
                clearInterval(animationInterval);
                animationInterval = null;
            }
        };
        
        // Start animation when not focused
        this.searchInput.addEventListener('blur', startAnimation);
        this.searchInput.addEventListener('focus', stopAnimation);
        
        // Start initially if not focused
        if (this.searchInput !== document.activeElement) {
            startAnimation();
        }
    }
    
    getSearchContext() {
        const url = window.location.pathname;
        if (url.includes('/users')) return 'users';
        if (url.includes('/themes')) return 'themes';
        if (url.includes('/ideas')) return 'ideas';
        return 'general';
    }
    
    getContextualPlaceholders(context, original) {
        const placeholderMap = {
            users: [
                'Rechercher par nom...',
                'Rechercher par prénom...',
                'Rechercher par email...',
                'Trouver un utilisateur...',
                original
            ],
            themes: [
                'Rechercher par nom...',
                'Rechercher par description...',
                'Trouver une thématique...',
                original
            ],
            ideas: [
                'Rechercher par titre...',
                'Rechercher par description...',
                'Rechercher par auteur...',
                'Trouver une idée...',
                original
            ],
            general: [
                'Rechercher...',
                'Tapez votre recherche...',
                original
            ]
        };
        
        return placeholderMap[context] || placeholderMap.general;
    }
    
    animatePlaceholderChange(newPlaceholder) {
        if (!this.searchInput) return;
        
        // Fade out
        this.searchInput.style.transition = 'opacity 0.2s ease';
        this.searchInput.style.opacity = '0.7';
        
        setTimeout(() => {
            this.searchInput.placeholder = newPlaceholder;
            // Fade in
            this.searchInput.style.opacity = '1';
        }, 200);
    }
    
    setupFormValidation() {
        if (!this.form) return;
        
        this.form.addEventListener('submit', (e) => {
            const searchValue = this.searchInput.value.trim();
            
            // Prevent submission of empty searches (optional)
            if (searchValue.length === 0) {
                // Uncomment to prevent empty searches
                // e.preventDefault();
                // this.showValidationMessage('Veuillez saisir un terme de recherche');
                // return;
            }
            
            // Add loading state
            this.setLoadingState(true);
        });
    }
    
    setLoadingState(isLoading) {
        const container = this.container.querySelector('.search-input-container');
        if (!container) return;
        
        if (isLoading) {
            container.classList.add('search-loading');
        } else {
            container.classList.remove('search-loading');
        }
    }
    
    showValidationMessage(message) {
        // Remove existing message
        this.hideValidationMessage();
        
        const messageEl = document.createElement('div');
        messageEl.className = 'search-validation-message';
        messageEl.style.cssText = `
            color: #dc2626;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            padding: 0.5rem;
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 0.375rem;
            animation: fadeIn 0.3s ease;
        `;
        messageEl.textContent = message;
        
        this.container.querySelector('.search-bar-wrapper').appendChild(messageEl);
        
        // Auto-hide after 3 seconds
        setTimeout(() => this.hideValidationMessage(), 3000);
    }
    
    hideValidationMessage() {
        const message = this.container.querySelector('.search-validation-message');
        if (message) {
            message.remove();
        }
    }
    
    setupKeyboardShortcuts() {
        // Focus search with Ctrl+F or Cmd+F
        document.addEventListener('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                // Only if we're on an admin page with search
                if (this.searchInput && window.location.pathname.includes('/admin/')) {
                    e.preventDefault();
                    this.focusSearch();
                }
            }
            
            // Clear search with Escape
            if (e.key === 'Escape' && this.searchInput === document.activeElement) {
                this.clearSearch();
            }
        });
    }
    
    focusSearch() {
        if (this.searchInput) {
            this.searchInput.focus();
            this.searchInput.select();
        }
    }
    
    setupAutoComplete() {
        if (!this.searchInput) return;
        
        let debounceTimeout;
        
        this.searchInput.addEventListener('input', (e) => {
            clearTimeout(debounceTimeout);
            
            debounceTimeout = setTimeout(() => {
                const query = e.target.value.trim();
                if (query.length >= 2) {
                    // Here you could implement autocomplete functionality
                    // this.showSuggestions(query);
                }
            }, 300);
        });
    }
    
    // Utility method to highlight search terms in results
    static highlightSearchTerms(text, searchTerm) {
        if (!searchTerm || !text) return text;
        
        const regex = new RegExp(`(${searchTerm.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
        return text.replace(regex, '<span class="search-highlight">$1</span>');
    }
}

// Auto-initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    const searchContainers = document.querySelectorAll('.modern-search-container');
    searchContainers.forEach(container => {
        new ModernSearch(container);
    });
});

// Export for manual initialization
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ModernSearch;
}
