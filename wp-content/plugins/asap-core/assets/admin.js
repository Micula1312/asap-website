jQuery(($) => {
  let frame;

  const $input = $('#asap_gallery');
  const $preview = $('#asap-gallery-preview');
  const $select = $('#asap-gallery-select');
  const $clear = $('#asap-gallery-clear');

  if (!$input.length || !$select.length) return;

  const renderPreview = (attachments) => {
    $preview.empty();
    attachments.forEach((attachment) => {
      const sizes = attachment.sizes || {};
      const src = sizes.thumbnail?.url || attachment.url;
      $('<img>', { src, alt: attachment.alt || '' }).appendTo($preview);
    });
  };

  $select.on('click', (event) => {
    event.preventDefault();

    if (frame) {
      frame.open();
      return;
    }

    frame = wp.media({
      title: 'Project gallery',
      button: { text: 'Use selected images' },
      library: { type: 'image' },
      multiple: true,
    });

    frame.on('open', () => {
      const selection = frame.state().get('selection');
      const ids = ($input.val() || '')
        .split(',')
        .map((id) => parseInt(id, 10))
        .filter(Boolean);

      ids.forEach((id) => {
        const attachment = wp.media.attachment(id);
        attachment.fetch();
        selection.add(attachment);
      });
    });

    frame.on('select', () => {
      const attachments = frame.state().get('selection').toJSON();
      $input.val(attachments.map((attachment) => attachment.id).join(','));
      renderPreview(attachments);
    });

    frame.open();
  });

  $clear.on('click', (event) => {
    event.preventDefault();
    $input.val('');
    $preview.empty();
  });
});
