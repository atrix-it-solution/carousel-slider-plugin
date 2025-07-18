// jQuery(document).ready(function($) {
//     // Add new slide
//           $('.add-slide').on('click', function(e) {
//         e.preventDefault();
//         var index = $('.carousel-slides-list tr.carousel-slide').length;
//         var newSlide = `
//             <tr class="carousel-slide" data-index="${index}">
//                 <td class="slide-handle">
//                     <h3>Slide ${index + 1}</h3>
//                 </td>
//                 <td>
//                     <input type="text" name="carousel_slides[${index}][heading]" value="" class="widefat">
//                 </td>
//                 <td>
//                     <textarea name="carousel_slides[${index}][description]" class="widefat"></textarea>
//                 </td>
//                 <td>
//                     <input type="hidden" class="image-id" name="carousel_slides[${index}][image_id]" value="">
//                     <div class="image-preview"><span>No image selected</span></div>
//                     <button class="upload-image-button button">Add image</button>
//                     <button class="remove-image-button button" style="display:none;">Remove</button>
//                 </td>
//                 <td>
//                     <input type="text" name="carousel_slides[${index}][button_text]" value="" class="widefat">
//                 </td>
//                 <td>
//                     <input type="url" name="carousel_slides[${index}][button_url]" value="" class="widefat">
//                 </td>
//                 <td>
//                     <button class="remove-slide button">Remove</button>
//                 </td>
//             </tr>
//         `;
//         $('.carousel-slides-list').append(newSlide);
//     });
    
//     // Remove slide
//     $('.carousel-slides-list').on('click', '.remove-slide', function(e) {
//         e.preventDefault();
//         // if (confirm('Are you sure you want to remove this slide?')) {
//             $(this).closest('.carousel-slide').remove();
//             // Reindex slides
//             $('.carousel-slide').each(function(index) {
//                 $(this).attr('data-index', index);
//                 $(this).find('h3').text('Slide ' + (index + 1));
//                 $(this).find('input, textarea').each(function() {
//                     var name = $(this).attr('name').replace(/\[\d+\]/, '[' + index + ']');
//                     $(this).attr('name', name);
//                 });
//             });
//         // }
//     });
    
//     // Make slides sortable
//     $('.carousel-slides-list').sortable({
//         handle: '.slide-handle',
//         update: function() {
//             // Reindex slides after sorting
//             $('.carousel-slide').each(function(index) {
//                 $(this).attr('data-index', index);
//                 $(this).find('h3').text('Slide ' + (index + 1));
//                 $(this).find('input, textarea').each(function() {
//                     var name = $(this).attr('name').replace(/\[\d+\]/, '[' + index + ']');
//                     $(this).attr('name', name);
//                 });
//             });
//         }
//     });
    
//     // Image upload
//     $('.carousel-slides-list').on('click', '.upload-image-button', function(e) {
//         e.preventDefault();
//         var button = $(this);
//         var imageIdInput = button.siblings('.image-id');
//         var imagePreview = button.siblings('.image-preview');
//         var removeButton = button.siblings('.remove-image-button');
        
//         var frame = wp.media({
//             title: 'Select or Upload Image',
//             button: {
//                 text: 'Use this image'
//             },
//             multiple: false
//         });
        
//         frame.on('select', function() {
//             var attachment = frame.state().get('selection').first().toJSON();
//             imageIdInput.val(attachment.id);
//             imagePreview.html('<img src="' + attachment.url + '" alt="" style="max-width:100%;height:auto;">');
//             removeButton.show();
//         });
        
//         frame.open();
//     });
    
//     // Image remove
//     $('.carousel-slides-list').on('click', '.remove-image-button', function(e) {
//         e.preventDefault();
//         var button = $(this);
//         var imageIdInput = button.siblings('.image-id');
//         var imagePreview = button.siblings('.image-preview');
        
//         imageIdInput.val('');
//         imagePreview.html('<span>No image selected</span>');
//         button.hide();
//     });
// });






jQuery(document).ready(function($) {
        // Add new slide at the bottom
    $('.add-slide').on('click', function(e) {
        e.preventDefault();
        addNewSlide($('.carousel-slides-list tr.carousel-slide').length);
    });
    
    // Add slide above
    $('.carousel-slides-list').on('click', '.add-slide-above', function(e) {
        e.preventDefault();
        var index = $(this).closest('tr').index();
        addNewSlide(index);
    });
    
    // Remove slide
    // $('.carousel-slides-list').on('click', '.remove-slide', function(e) {
    //     e.preventDefault();
    //     // if (confirm('Are you sure you want to remove this slide?')) {
    //         $(this).closest('tr').remove();
    //         reindexAllSlides();
    //     // }
    // });
        // Remove slide with confirmation dialog

    $('.carousel-slides-list').on('click', '.remove-slide', function(e) {
        e.preventDefault();
        
        var $removeButton = $(this);
        var buttonPosition = $removeButton.offset();
        
        // Create and position the confirmation dialog
        var $dialog = $(
            '<div class="carousel-confirm-dialog" style="position: absolute; top: ' + (buttonPosition.top - 40) + 'px; left: ' + (buttonPosition.left - 80) + 'px; background: rgba(0, 0, 0, 1) ; color:white; padding: 2px;  z-index: 10000;">' +
                '<p style="margin: 0 0 10px 0;">Are you sure? <span  class="button confirm-remove" style="color:red; margin-right: 5px;">Remove</span></p>' +
            '</div>'
        );
        
        // Create overlay
        var $overlay = $('<div class="carousel-confirm-overlay" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 9999;"></div>');    
        
        $('body').append($overlay).append($dialog);
        
        // Confirm removal
        $dialog.on('click', '.confirm-remove', function() {
            $removeButton.closest('tr').remove();
            reindexAllSlides();
            $dialog.remove();
            $overlay.remove();
        });
        
        // Cancel removal
        $dialog.on('click', '.cancel-remove', function() {
            $dialog.remove();
            $overlay.remove();
        });
        
        // Close when clicking outside
        $overlay.on('click', function() {
            $dialog.remove();
            $overlay.remove();
        });
    });
    
    // Make slides sortable
    $('.carousel-slides-list').sortable({
        handle: '.slide-handle',
        update: function() {
            reindexAllSlides();
        }
    });



// Function to handle button URL visibility
    function handleButtonUrlVisibility($row) {
        const $buttonText = $row.find('input[name*="[button_text]"]');
        const $buttonUrlCell = $row.find('.button-url-cell');
        const $buttonUrlInput = $row.find('input[name*="[button_url]"]');
        
        if ($buttonText.val().trim() !== '') {
            $buttonUrlCell.removeClass('hidden');
            $buttonUrlInput.attr('required', 'required');
        } else {
            $buttonUrlCell.addClass('hidden');
            $buttonUrlInput.removeAttr('required').val('');
        }
        
        updateButtonUrlHeaderVisibility();
    }
    
    // Update header visibility
    function updateButtonUrlHeaderVisibility() {
        const hasVisibleButtonUrls = $('.carousel-slides-list .button-url-cell:not(.hidden)').length > 0;
        $('.button-url-header').toggleClass('hidden', !hasVisibleButtonUrls);
    }
    
    // Initialize on page load
    $('.carousel-slides-list tr').each(function() {
        handleButtonUrlVisibility($(this));
    });
    
    // Handle button text changes
    $('.carousel-slides-list').on('input', 'input[name*="[button_text]"]', function() {
        handleButtonUrlVisibility($(this).closest('tr'));
    });
    


    // Function to add new slide
    function addNewSlide(index) {
        var newSlide = `
            <tr class="carousel-slide" data-index="${index}">
                <td class="slide-handle">
                    <h3>Slide ${index + 1}</h3>
                </td>
                <td>
                    <input type="text" name="carousel_slides[${index}][heading]" value="" class="widefat">
                </td>
                <td>
                    <textarea name="carousel_slides[${index}][description]" class="widefat"></textarea>
                </td>
                <td>
                    <input type="hidden" class="image-id" name="carousel_slides[${index}][image_id]" value="">
                    <div class="image-preview"><span>No image selected</span></div>
                    <button class="upload-image-button button">Add image</button>
                    <button class="remove-image-button button" style="display:none;">Remove</button>
                </td>
                 <td>
                    <input type="text" name="carousel_slides[${index}][button_text]" value="" class="widefat">
                </td>
                <td class="button-url-cell hidden">
                    <input type="url" name="carousel_slides[${index}][button_url]" value="" class="widefat">
                </td>
                 <td >
                            <div class="slide-actions">
                                <button class="slide-action add-slide-above" title="Add slide above">+</button>
                                <button class="slide-action remove-slide" title="Remove slide">-</button>
                            </div>
                        </td>
            </tr>
        `;
        
        if (index === $('.carousel-slides-list tr.carousel-slide').length) {
            $('.carousel-slides-list').append(newSlide);
        } else {
            $('.carousel-slides-list tr.carousel-slide').eq(index).before(newSlide);
        }
        
        reindexAllSlides();
    }

    // Reindex all slides
    function reindexAllSlides() {
        $('.carousel-slides-list tr.carousel-slide').each(function(newIndex) {
            // Update data-index attribute
            $(this).attr('data-index', newIndex);
            
            // Update displayed slide number
            $(this).find('h3').text('Slide ' + (newIndex + 1));
            
            // Update all input names
            $(this).find('input, textarea').each(function() {
                var name = $(this).attr('name');
                name = name.replace(/carousel_slides\[\d+\]/, 'carousel_slides[' + newIndex + ']');
                $(this).attr('name', name);
            });
        });
    }
    
    // Image upload
    $('.carousel-slides-list').on('click', '.upload-image-button', function(e) {
        e.preventDefault();
        var button = $(this);
        var imageIdInput = button.siblings('.image-id');
        var imagePreview = button.siblings('.image-preview');
        var removeButton = button.siblings('.remove-image-button');
        
        var frame = wp.media({
            title: 'Select or Upload Image',
            button: {
                text: 'Use this image'
            },
            multiple: false
        });
        
        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            imageIdInput.val(attachment.id);
            imagePreview.html('<img src="' + attachment.url + '" alt="" style="max-width:100%;height:auto;">');
            removeButton.show();
        });
        
        frame.open();
    });
    
    // Image remove
    $('.carousel-slides-list').on('click', '.remove-image-button', function(e) {
        e.preventDefault();
        var button = $(this);
        var imageIdInput = button.siblings('.image-id');
        var imagePreview = button.siblings('.image-preview');
        
        imageIdInput.val('');
        imagePreview.html('<span>No image selected</span>');
        button.hide();
    });
});



