jQuery(document).ready(function ($) {
  let cropper;

  // Busca as dimensões de recorte salvas no WordPress
  const cropWidth = parseInt(icpCropSettings.width, 10) || 800;
  const cropHeight = parseInt(icpCropSettings.height, 10) || 600;

  $(document).on('click', '.icp-crop-button', function () {
    let imageUrl = document.querySelector('.details-image').src;
    const baseUrl = 'wp-content';
    imageUrl = location.origin + '/teste/wp-content' + (imageUrl.split(baseUrl)[1]);

    if (imageUrl) {
      $('.media-modal').addClass('behindPopup');
      $('.media-modal-backdrop').addClass('media-modal-backdrop-behind-popup');
      $('#icp-crop-image').attr('src', imageUrl);

      $('#icp-crop-popup').dialog({
        modal: true,
        width: '80%',
        height: 'auto',
        maxHeight: 1300,
        buttons: [
          {
            text: 'Recortar',
            class: 'button-primary',
            click: function () {
              if (cropper) {
                const canvas = cropper.getCroppedCanvas({
                  width: cropWidth,
                  height: cropHeight,
                });
                const croppedImage = canvas.toDataURL('image/jpeg'); // Converte para base64

                // Envia a imagem recortada para o WordPress
                $.post(ajaxurl, {
                  action: 'icp_save_cropped_image',
                  attachment_id: $('.icp-crop-button').data('attachment-id'),
                  cropped_image: croppedImage,
                }, function (response) {
                  if (response.success) {
                    alert('A imagem foi recortada e salva com sucesso!');
                    location.reload();
                  } else {
                    alert('Erro ao salvar a imagem: ' + response.data);
                  }
                });

                // Remove o cropper e fecha o popup
                cropper.destroy();
                $('#icp-crop-popup').dialog('close');
                $('.media-modal').removeClass('behindPopup');
                $('.media-modal-backdrop').removeClass('media-modal-backdrop-behind-popup');
              }
            },
          },
          {
            text: 'Cancelar',
            class: 'button',
            click: function () {
              $('.media-modal').removeClass('behindPopup');
              $('.media-modal-backdrop').removeClass('media-modal-backdrop-behind-popup');
              if (cropper) cropper.destroy();
              $(this).dialog('close');
            },
          },
        ],
        open: function () {
          const image = document.getElementById('icp-crop-image');
          cropper = new Cropper(image, {
            aspectRatio: cropWidth / cropHeight,
            viewMode: 1,
            autoCropArea: 1,
            minCropBoxWidth: cropWidth,
            minCropBoxHeight: cropHeight,
          });

          const dialogHeight = Math.min($(window).height() * 0.9, 1300);
          $(this).dialog('option', 'height', dialogHeight);
        },
      });
    } else {
      alert('A URL da imagem não pôde ser encontrada.');
    }
  });
});
