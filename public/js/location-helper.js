// JavaScript helper untuk frontend integration
// Gunakan di form registration untuk auto-complete city

class LocationHelper {
    constructor(apiBaseUrl = '') {
        this.apiBaseUrl = apiBaseUrl;
        this.debounceTimer = null;
    }

    // Auto-complete city dengan suggestion
    async searchCities(query, callback) {
        // Debounce untuk performa
        clearTimeout(this.debounceTimer);
        
        this.debounceTimer = setTimeout(async () => {
            if (query.length < 2) {
                callback([]);
                return;
            }

            try {
                const response = await fetch(`${this.apiBaseUrl}/api/location/smart-search?q=${encodeURIComponent(query)}`);
                const data = await response.json();

                if (data.success && data.data) {
                    const suggestions = data.data.map(item => ({
                        value: item.regency_name,
                        label: item.full_name,
                        data: {
                            regency_id: item.regency_id,
                            regency_name: item.regency_name,
                            province_name: item.province_name
                        }
                    }));
                    callback(suggestions);
                } else {
                    callback([]);
                }
            } catch (error) {
                console.error('Location search error:', error);
                callback([]);
            }
        }, 300); // 300ms debounce
    }

    // Auto-fill location fields saat user pilih city
    fillLocationFields(selectedCity, formFields) {
        if (selectedCity.data) {
            const { regency_id, regency_name, province_name } = selectedCity.data;
            
            if (formFields.regency_id) formFields.regency_id.value = regency_id;
            if (formFields.regency_name) formFields.regency_name.value = regency_name;
            if (formFields.province_name) formFields.province_name.value = province_name;
        }
    }
}

// Example usage:
/*
const locationHelper = new LocationHelper('https://www.amazingsultrarun.com');

// Setup autocomplete for city field
const cityInput = document.getElementById('city');
const suggestionsContainer = document.getElementById('city-suggestions');

cityInput.addEventListener('input', (e) => {
    locationHelper.searchCities(e.target.value, (suggestions) => {
        // Display suggestions
        suggestionsContainer.innerHTML = '';
        suggestions.forEach(suggestion => {
            const div = document.createElement('div');
            div.className = 'suggestion-item';
            div.textContent = suggestion.label;
            div.onclick = () => {
                cityInput.value = suggestion.value;
                
                // Auto-fill location fields
                locationHelper.fillLocationFields(suggestion, {
                    regency_id: document.getElementById('regency_id'),
                    regency_name: document.getElementById('regency_name'),
                    province_name: document.getElementById('province_name')
                });
                
                suggestionsContainer.innerHTML = '';
            };
            suggestionsContainer.appendChild(div);
        });
    });
});
*/
