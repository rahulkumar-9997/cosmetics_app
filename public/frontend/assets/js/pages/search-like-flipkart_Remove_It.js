$(document).ready(function () {
    /**open modal click search icon */
    $(document).on('click', '.search-modal-open', function(e) {
        e.preventDefault();
        let route = $(this).data('route');
        var data = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            url: route,
        };
        $.ajax({
            url: route,
            type: 'GET',
            data: data,
            success: function (data) {
                $('#search .search-modal-render-data').html(data.searchModel);
                $("#search").modal('show');
            },
            error: function (data) {
                data = data.responseJSON;
            }
        });
     });
    /**open modal click search icon */

    let selectedIndex = -1;
    let recentSearches = JSON.parse(localStorage.getItem('recentSearches')) || [];
    
    $('#search-input, #search-input-mobile').on('input', function() {
        let query = $(this).val().trim();
        if (query.length > 1) {
            fetchSuggestions(query);
        } else {
            showRecentSearches();
        }
    });
    
    $('#search-input, #search-input-mobile').on('focus', function() {
        let query = $(this).val().trim();
        if (query.length === 0) showRecentSearches();
    });
    
    function fetchSuggestions(query) {
        let formAction = searchSuggestionUrl;
        $.ajax({
            url: formAction,
            method: 'GET',
            data: { query: query },
            success: function(data) {
                showSuggestions(data.suggestions, query);
            },
            error: function() {
                console.log('Error fetching suggestions');
            }
        });
    }
    
    function showSuggestions(suggestions, query) {
        let suggestionsList = $('.suggestions');
        suggestionsList.empty();
        selectedIndex = -1;
    
        if (suggestions.length > 0) {
            // Group suggestions by category
            let grouped = {};
            suggestions.forEach(suggestion => {
                if (!grouped[suggestion.category]) {
                    grouped[suggestion.category] = [];
                }
                grouped[suggestion.category].push(suggestion);
            });
    
            // Display each category
            for (let category in grouped) {
                let categoryHeader = $(`<div class="suggestion-category">${category}</div>`);
                suggestionsList.append(categoryHeader);
    
                grouped[category].forEach(suggestion => {
                    let suggestionItem = $('<li>').addClass('suggestion-item');
                    
                    let html = `
                        <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                            <div style="display: flex; align-items: center;">
                    `;
                    
                    if (suggestion.image) {
                        html += `
                            <div class="suggestion-img">
                                <img src="${suggestion.image}" alt="${suggestion.title}">
                            </div>
                        `;
                    } else {
                        html += `<i class="icon icon-search" style="font-size: 14px; margin-right: 5px;"></i>`;
                    }
                    
                    html += `<span>${highlightMatch(suggestion.title, query)}</span>`;
                    html += `</div>`; // Close flex div
                    
                    if (suggestion.price) {
                        html += `<div class="suggestion-price">₹${suggestion.price}</div>`;
                    }
                    
                    html += `</div>`; // Close main flex div
                    
                    suggestionItem.html(html);
                    suggestionItem.data('url', suggestion.url);
                    
                    suggestionItem.on('click', function() {
                        selectSuggestion(suggestion.title, suggestion.url);
                    });
    
                    suggestionsList.append(suggestionItem);
                });
            }
        } else {
            suggestionsList.append('<li class="no-suggestions">No suggestions found</li>');
        }
        
        suggestionsList.show();
    }
    
    function showRecentSearches() {
        let suggestionsList = $('.suggestions');
        suggestionsList.empty();
        selectedIndex = -1;
    
        if (recentSearches.length > 0) {
            let header = $(`<div class="suggestion-category">Recent Searches</div>`);
            suggestionsList.append(header);
    
            recentSearches.forEach(function(search) {
                let item = $('<li>')
                    .addClass('suggestion-item')
                    .html(`
                        <div style="display: flex; align-items: center;">
                            <i class="icon icon-history" style="font-size: 14px; margin-right: 5px;"></i>
                            <span>${search}</span>
                        </div>
                    `)
                    .data('url', `${searchUrl}?query=${encodeURIComponent(search)}`)
                    .on('click', function() {
                        selectSuggestion(search, $(this).data('url'));
                    });
                suggestionsList.append(item);
            });
            
            suggestionsList.show();
        }
    }
    
    function highlightMatch(text, query) {
        let regex = new RegExp('(' + query + ')', 'gi');
        return text.replace(regex, '<span class="highlight">$1</span>');
    }
    
    function selectSuggestion(value, url = null) {
        $('#search-input, #search-input-mobile').val(value);
        saveToRecentSearches(value);
        
        if (url) {
            window.location.href = url;
        } else {
            autoSubmitForm(value);
        }
        
        $('.suggestions').hide();
    }
    
    function saveToRecentSearches(value) {
        // Remove if already exists
        recentSearches = recentSearches.filter(item => item.toLowerCase() !== value.toLowerCase());
        
        // Add to beginning
        recentSearches.unshift(value);
        
        // Keep only 5 items
        if (recentSearches.length > 5) {
            recentSearches.pop();
        }
        
        localStorage.setItem('recentSearches', JSON.stringify(recentSearches));
    }
    
    // Keyboard navigation remains the same
    $('#search-input, #search-input-mobile').on('keydown', function(e) {
        let suggestions = $('.suggestions li.suggestion-item'); 
        if (suggestions.length === 0) return;
        
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            selectedIndex = (selectedIndex + 1) % suggestions.length; 
            updateSuggestionHighlight(suggestions);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            selectedIndex = (selectedIndex - 1 + suggestions.length) % suggestions.length; 
            updateSuggestionHighlight(suggestions);
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (selectedIndex >= 0) {
                let selectedItem = $(suggestions[selectedIndex]);
                selectSuggestion(selectedItem.text().trim(), selectedItem.data('url'));
            } else {
                autoSubmitForm($(this).val().trim());
            }
        }
    });
    
    function updateSuggestionHighlight(suggestions) {
        suggestions.removeClass('selected'); 
        if (selectedIndex >= 0) {
            let activeItem = $(suggestions[selectedIndex]);
            activeItem.addClass('selected');
            $('#search-input, #search-input-mobile').val(activeItem.text().trim());
        }
    }
    
    function autoSubmitForm(query) {
        let form = $('#search-input').is(':focus') 
            ? $('#search-form') 
            : $('#search-mobile-form');
        if (!form.length || !form.attr('action')) {
            form = $('#search-form');
        }
        
        let actionUrl = form.attr('action') + '?query=' + encodeURIComponent(query);
        window.location.href = actionUrl;
    }
    
    // Hide suggestions when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.search-box').length) {
            $('.suggestions').hide();
        }
    });
});
