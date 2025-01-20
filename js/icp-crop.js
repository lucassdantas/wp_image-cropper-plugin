jQuery(document).ready(function ($) {
  $(document).on('click', '.icp-crop-button', function () {
    let imageUrl = document.querySelector('.details-image').src
    console.log(imageUrl)
    // Mostra o pop-up com a imagem carregada
      if (imageUrl) {
          $('.media-modal').addClass('behindPopup')
          $('.media-modal-backdrop').addClass('media-modal-backdrop-behind-popup')
          $('#icp-crop-image').attr('src', imageUrl); 
          $('#icp-crop-popup').dialog({
              modal: true,
              width: 850,
              height: 700,
              buttons: {
                  Close: function () {
                      $('.media-modal').removeClass('behindPopup')
                      $('.media-modal-backdrop').removeClass('media-modal-backdrop-behind-popup')
                      $(this).dialog('cancelar');
                  },
              },
              close: () => {
                $('.media-modal').removeClass('behindPopup')
                $('.media-modal-backdrop').removeClass('media-modal-backdrop-behind-popup')
              }
          });
      } else alert('A URL da imagem não pôde ser encontrada.');
  });
});
