/**
 * Location Autocomplete Component
 * Handles city/regency autocomplete functionality
 */
class LocationAutocomplete {
    constructor(inputSelector, hiddenInputSelector = null) {
        this.input = document.querySelector(inputSelector);
        this.hiddenInput = hiddenInputSelector ? document.querySelector(hiddenInputSelector) : null;
        this.suggestionsList = null;
        this.selectedIndex = -1;
        this.suggestions = [];
        this.debounceTimer = null;
        
        this.init();
    }
    
    init() {
        if (!this.input) {
            console.error('Input element not found');
            return;
        }
        
        this.createSuggestionsList();
        this.attachEventListeners();
    }
    
    createSuggestionsList() {
        this.suggestionsList = document.createElement('ul');
        this.suggestionsList.className = 'location-suggestions';
        this.suggestionsList.style.cssText = `
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-top: none;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            list-style: none;
            margin: 0;
            padding: 0;
            display: none;
        `;
        
        // Make input container relative
        const container = this.input.parentElement;
        if (container.style.position !== 'relative') {
            container.style.position = 'relative';
        }
        
        container.appendChild(this.suggestionsList);
    }
    
    attachEventListeners() {
        // Input events
        this.input.addEventListener('input', (e) => this.handleInput(e));
        this.input.addEventListener('keydown', (e) => this.handleKeydown(e));
        this.input.addEventListener('blur', (e) => this.handleBlur(e));
        this.input.addEventListener('focus', (e) => this.handleFocus(e));
        
        // Click outside to close
        document.addEventListener('click', (e) => {
            if (!this.input.contains(e.target) && !this.suggestionsList.contains(e.target)) {
                this.hideSuggestions();
            }
        });
    }
    
    handleInput(e) {
        const query = e.target.value.trim();
        
        // Clear debounce timer
        if (this.debounceTimer) {
            clearTimeout(this.debounceTimer);
        }
        
        // Clear hidden input if text is changed
        if (this.hiddenInput) {
            this.hiddenInput.value = '';
        }
        
        if (query.length < 2) {
            this.hideSuggestions();
            return;
        }
        
        // Debounce search
        this.debounceTimer = setTimeout(() => {
            this.searchLocations(query);
        }, 300);
    }
    
    handleKeydown(e) {
        if (!this.suggestionsList.style.display || this.suggestionsList.style.display === 'none') {
            return;
        }
        
        switch (e.key) {
            case 'ArrowDown':
                e.preventDefault();
                this.selectNext();
                break;
            case 'ArrowUp':
                e.preventDefault();
                this.selectPrevious();
                break;
            case 'Enter':
                e.preventDefault();
                this.selectCurrent();
                break;
            case 'Escape':
                this.hideSuggestions();
                break;
        }
    }
    
    handleBlur(e) {
        // Delay hiding to allow click on suggestions
        setTimeout(() => {
            this.hideSuggestions();
        }, 150);
    }
    
    handleFocus(e) {
        const query = e.target.value.trim();
        if (query.length >= 2) {
            this.searchLocations(query);
        }
    }
    
    async searchLocations(query) {
        console.log('Searching for:', query); // Debug log
        
        try {
            // Always use relative path to avoid HTTPS/HTTP mixed content issues
            let baseUrl = '/api/location/search';
            
            // If window.locationSearchUrl is set and is relative, use it
            if (window.locationSearchUrl && window.locationSearchUrl.startsWith('/')) {
                baseUrl = window.locationSearchUrl;
            }
            
            const url = `${baseUrl}?q=${encodeURIComponent(query)}`;
            console.log('Fetching URL:', url); // Debug log
            
            // Add timeout and better error handling
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 10000); // 10 second timeout
            
            const response = await fetch(url, {
                signal: controller.signal,
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            clearTimeout(timeoutId);
            console.log('Response status:', response.status); // Debug log
            
            if (!response.ok) {
                console.error('Response not OK:', response.status, response.statusText);
                // Show fallback suggestions instead of completely failing
                this.showFallbackSuggestions(query);
                return;
            }
            
            const suggestions = await response.json();
            console.log('Suggestions received:', suggestions); // Debug log
            
            this.showSuggestions(suggestions);
            
        } catch (error) {
            console.error('Location search error:', error);
            
            // Check if it's a network error or server block
            if (error.name === 'TypeError' && error.message.includes('Failed to fetch')) {
                console.warn('Network request blocked or failed, showing fallback suggestions');
                this.showFallbackSuggestions(query);
            } else if (error.name === 'AbortError') {
                console.warn('Request timeout, showing fallback suggestions');
                this.showFallbackSuggestions(query);
            } else {
                this.hideSuggestions();
            }
        }
    }
    
    showFallbackSuggestions(query) {
        // Common Indonesian cities/regencies as fallback
        const fallbackData = [
            { id: '001', name: 'Kendari', province_name: 'Sulawesi Tenggara' },
            { id: '002', name: 'Kolaka', province_name: 'Sulawesi Tenggara' },
            { id: '003', name: 'Bau-Bau', province_name: 'Sulawesi Tenggara' },
            { id: '004', name: 'Makassar', province_name: 'Sulawesi Selatan' },
            { id: '005', name: 'Jakarta Pusat', province_name: 'DKI Jakarta' },
            { id: '006', name: 'Surabaya', province_name: 'Jawa Timur' },
            { id: '007', name: 'Bandung', province_name: 'Jawa Barat' },
            { id: '008', name: 'Yogyakarta', province_name: 'DI Yogyakarta' },
            { id: '009', name: 'Semarang', province_name: 'Jawa Tengah' },
            { id: '010', name: 'Medan', province_name: 'Sumatera Utara' }
        ];
        
        // Filter fallback data based on query
        const filtered = fallbackData.filter(item => 
            item.name.toLowerCase().includes(query.toLowerCase()) ||
            item.province_name.toLowerCase().includes(query.toLowerCase())
        );
        
        console.log('Showing fallback suggestions:', filtered);
        this.showSuggestions(filtered);
    }
    
    showSuggestions(suggestions) {
        this.suggestions = suggestions;
        this.selectedIndex = -1;
        
        // Clear existing suggestions
        this.suggestionsList.innerHTML = '';
        
        if (suggestions.length === 0) {
            const li = document.createElement('li');
            li.textContent = 'Tidak ada hasil ditemukan';
            li.style.cssText = `
                padding: 10px;
                color: #666;
                font-style: italic;
            `;
            this.suggestionsList.appendChild(li);
        } else {
            suggestions.forEach((suggestion, index) => {
                const li = document.createElement('li');
                li.innerHTML = `
                    <div style="padding: 10px; cursor: pointer; border-bottom: 1px solid #eee;">
                        <div style="font-weight: 500;">${suggestion.name}</div>
                        <div style="font-size: 0.9em; color: #666;">${suggestion.province_name}</div>
                    </div>
                `;
                
                li.addEventListener('click', () => {
                    this.selectSuggestion(suggestion);
                });
                
                li.addEventListener('mouseenter', () => {
                    this.selectedIndex = index;
                    this.updateSelection();
                });
                
                this.suggestionsList.appendChild(li);
            });
        }
        
        this.suggestionsList.style.display = 'block';
    }
    
    hideSuggestions() {
        this.suggestionsList.style.display = 'none';
        this.selectedIndex = -1;
    }
    
    selectNext() {
        if (this.selectedIndex < this.suggestions.length - 1) {
            this.selectedIndex++;
            this.updateSelection();
        }
    }
    
    selectPrevious() {
        if (this.selectedIndex > 0) {
            this.selectedIndex--;
            this.updateSelection();
        }
    }
    
    selectCurrent() {
        if (this.selectedIndex >= 0 && this.selectedIndex < this.suggestions.length) {
            this.selectSuggestion(this.suggestions[this.selectedIndex]);
        }
    }
    
    updateSelection() {
        const items = this.suggestionsList.children;
        
        for (let i = 0; i < items.length; i++) {
            const item = items[i];
            if (i === this.selectedIndex) {
                item.style.backgroundColor = '#f0f0f0';
            } else {
                item.style.backgroundColor = '';
            }
        }
    }
    
    selectSuggestion(suggestion) {
        this.input.value = suggestion.label;
        
        if (this.hiddenInput) {
            this.hiddenInput.value = suggestion.id;
        }
        
        // Store additional data as data attributes
        this.input.dataset.regencyId = suggestion.id;
        this.input.dataset.regencyName = suggestion.name;
        this.input.dataset.provinceName = suggestion.province_name;
        
        this.hideSuggestions();
        
        // Trigger custom event
        const event = new CustomEvent('locationSelected', {
            detail: suggestion
        });
        this.input.dispatchEvent(event);
    }
}

// Auto-initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Initialize for elements with data-location-autocomplete attribute
    const autocompleteInputs = document.querySelectorAll('[data-location-autocomplete]');
    
    autocompleteInputs.forEach(input => {
        const hiddenInputSelector = input.dataset.hiddenInput || null;
        new LocationAutocomplete(`#${input.id}`, hiddenInputSelector);
    });
});

// Export for manual initialization
window.LocationAutocomplete = LocationAutocomplete;
