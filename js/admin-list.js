jQuery(document).ready(function($) {
    $('.copy-shortcode').on('click', function(e) {
        e.preventDefault();
        var $button = $(this);
        var shortcode = $button.data('clipboard-text') || $button.prev('code').text();
        
        // Create temporary input element
        var $temp = $('<input>');
        $('body').append($temp);
        $temp.val(shortcode).select();
        
        try {
            // Execute copy command
            var successful = document.execCommand('copy');
            if (successful) {
                // Show feedback
                var originalText = $button.text();
                $button.text('Copied!').addClass('copied');
                setTimeout(function() {
                    $button.text(originalText).removeClass('copied');
                }, 2000);
            }
        } catch (err) {
            console.error('Failed to copy text: ', err);
        }
        
        // Remove temporary input
        $temp.remove();
    });
});