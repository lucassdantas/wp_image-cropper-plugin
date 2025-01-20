jQuery(document).ready(function ($) {
  let cropper;

  $(document).on('click', '.icp-crop-button', function () {
    let imageUrl = document.querySelector('.details-image').src;
    const baseUrl = 'wp-content';
    imageUrl = location.origin+'/teste/wp-content'+(imageUrl.split(baseUrl)[1])
    console.log(imageUrl)
    if (imageUrl) {
      $('.media-modal').addClass('behindPopup');
      $('.media-modal-backdrop').addClass('media-modal-backdrop-behind-popup');
      $('#icp-crop-image').attr('src', imageUrl);

      $('#icp-crop-popup').dialog({
        modal: true,
        width: 850,
        height: 700,
        buttons: {
          Close: function () {
            $('.media-modal').removeClass('behindPopup');
            $('.media-modal-backdrop').removeClass('media-modal-backdrop-behind-popup');
            cropper.destroy(); // Destroi o cropper ao fechar
            $(this).dialog('close');
          },
        },
        open: function () {
          const image = document.getElementById('icp-crop-image');
          cropper = new Cropper(image, {
            aspectRatio: 800 / 600, // Define a proporção fixa de 800x600
            viewMode: 1,
            autoCropArea: 1,
            minCropBoxWidth: 800,
            minCropBoxHeight: 600,
          });
        },
      });
    } else {
      alert('A URL da imagem não pôde ser encontrada.');
    }
  });

  // Salva a imagem recortada
  $(document).on('click', '#icp-save-crop', function () {
    if (cropper) {
      const canvas = cropper.getCroppedCanvas({
        width: 800,
        height: 600,
      });
      const croppedImage = canvas.toDataURL('image/jpeg'); // Converte para base64

      // Exemplo de como exibir ou enviar a imagem recortada
      console.log('Imagem recortada:', croppedImage);

      // Remova o cropper e feche o popup
      cropper.destroy();
      $('#icp-crop-popup').dialog('close');
      $('.media-modal').removeClass('behindPopup');
      $('.media-modal-backdrop').removeClass('media-modal-backdrop-behind-popup');
    }
  });
});
