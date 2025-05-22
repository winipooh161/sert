      canvas.renderAll();
      
      // Отправляем стикер на сервер для будущего использования
      if (typeof uploadStickerToServer === 'function') {
        uploadStickerToServer(file);
      }
    });
  };
  
  reader.readAsDataURL(file);
}

// Отправка стикера на сервер
function uploadStickerToServer(file) {
  const formData = new FormData();
  formData.append('sticker', file);
  formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
  
  fetch('/sticker-upload', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      console.log('Стикер успешно сохранен на сервере');
    }
  })
  .catch(error => {
    console.error('Ошибка при загрузке стикера:', error);
  });
}
<?php /**PATH C:\OSPanel\domains\sert\resources\views/photo-editor/partials/js/_stickers.blade.php ENDPATH**/ ?>