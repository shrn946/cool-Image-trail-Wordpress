jQuery(document).ready(function($) {
    function createImageItem(url = '') {
        return `
            <div class="image-trail-item">
                <img src="${url}" />
                <input type="hidden" name="image_trail_images[]" value="${url}" />
                <button type="button" class="button remove-image">Remove</button>
            </div>
        `;
    }

    $('#upload-images-button').on('click', function(e) {
        e.preventDefault();

        const frame = wp.media({
            title: 'Select Images',
            button: { text: 'Add Images' },
            multiple: true
        });

        frame.on('select', function() {
            const selection = frame.state().get('selection');
            selection.map(attachment => {
                const url = attachment.toJSON().url;
                $('#image-trail-images-wrapper').append(createImageItem(url));
            });
        });

        frame.open();
    });

    $('#image-trail-images-wrapper').on('click', '.remove-image', function(e) {
        e.preventDefault();
        $(this).closest('.image-trail-item').remove();
    });
});


document.addEventListener("DOMContentLoaded", () => {
    const title = document.querySelector('.content__title');
    const text = title.textContent;
    title.innerHTML = '';

    [...text].forEach((char, index) => {
      const span = document.createElement('span');
      span.textContent = char;
      span.style.setProperty('--i', index);
      title.appendChild(span);
    });
  });