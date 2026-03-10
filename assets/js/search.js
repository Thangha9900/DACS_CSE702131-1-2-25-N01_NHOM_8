$(document).ready(function() {
    const searchInput = $('#search-input');
    const autocompleteResults = $('#autocomplete-results');

    searchInput.on('input', function() {
        const keyword = $(this).val().trim();
        
        if (keyword.length < 2) {
            autocompleteResults.removeClass('show').empty();
            return;
        }


        $.ajax({
            url: 'api/search_api.php',
            method: 'POST',
            data: {
                action: 'search_suggest',
                keyword: keyword
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success' && response.suggestions.length > 0) {
                    let html = '';
                    response.suggestions.forEach(function(suggestion) {
                        html += '<div class="autocomplete-item" onclick="selectSuggestion(\'' + suggestion.replace(/'/g, "\\'") + '\')">' + 
                                escapeHtml(suggestion) + '</div>';
                    });
                    autocompleteResults.html(html).addClass('show');
                } else {
                    autocompleteResults.removeClass('show').empty();
                }
            },
            error: function() {
                autocompleteResults.removeClass('show').empty();
            }
        });
    });


    $(document).on('click', function(e) {
        if (!$(e.target).closest('.search-form-container').length) {
            autocompleteResults.removeClass('show');
        }
    });

    // Close autocomplete when pressing Escape
    searchInput.on('keydown', function(e) {
        if (e.key === 'Escape') {
            autocompleteResults.removeClass('show').empty();
        }
    });
});


function selectSuggestion(suggestion) {
    $('#search-input').val(suggestion);
    $('#autocomplete-results').removeClass('show').empty();

}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function addCart(productId, quantity) {
    $.ajax({
        url: 'api/ajax_request.php',
        method: 'POST',
        data: {
            action: 'cart',
            id: productId,
            num: quantity
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $('.cart_count').text(response.cartCount);
                $('.badge-cart').text(response.cartCount);
                showNotification('✓ Đã thêm vào giỏ hàng!', 'success');
            } else {
                showNotification('Lỗi: không thể thêm vào giỏ hàng', 'error');
            }
        },
        error: function() {
            showNotification('Có lỗi xảy ra khi thêm vào giỏ hàng!', 'error');
        }
    });
}
