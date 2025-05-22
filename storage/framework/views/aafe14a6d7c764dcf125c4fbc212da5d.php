// Загрузка изображения на canvas
function loadImage(file) {
  // Проверяем, что файл существует
  if (!file) return;
  
  const reader = new FileReader();
  reader.onload = function(e) {
    fabric.Image.fromURL(e.target.result, function(img) {
      // Очищаем холст и историю
      canvas.clear();
      undoHistory = [];
      redoHistory = [];
      
      // Сохраняем оригинальные размеры изображения
      image = img;
      isImageLoaded = true;
      
      // Масштабируем изображение под размер канваса
      const container = document.querySelector('.canvas-container');
      const containerWidth = container.clientWidth;
      const containerHeight = container.clientHeight;
      
      const imgRatio = img.width / img.height;
      const containerRatio = containerWidth / containerHeight;
      
      let canvasWidth, canvasHeight;
      
      if (imgRatio > containerRatio) {
        // Изображение шире контейнера
        canvasWidth = containerWidth;
        canvasHeight = canvasWidth / imgRatio;
      } else {
        // Изображение выше контейнера
        canvasHeight = containerHeight;
        canvasWidth = canvasHeight * imgRatio;
      }
      
      canvas.setWidth(canvasWidth);
      canvas.setHeight(canvasHeight);
      
      // Масштабируем и центрируем изображение
      img.scaleToWidth(canvas.getWidth());
      img.set({
        originX: 'center',
        originY: 'center',
        left: canvas.getWidth() / 2,
        top: canvas.getHeight() / 2,
        selectable: false, // Базовое изображение не должно быть выбираемым
        name: 'mainImage' // Добавляем имя для идентификации
      });
      
      canvas.add(img);
      canvas.renderAll();
      
      // Скрываем плейсхолдер для загрузки
      const placeholder = document.getElementById('upload-placeholder');
      if (placeholder) {
        placeholder.style.display = 'none';
        // Явно устанавливаем z-index, чтобы гарантировать, что плейсхолдер скрыт
        placeholder.style.zIndex = '-1';
        placeholder.classList.add('hidden');
      }
      
      // Сохраняем начальное состояние
      saveToHistory();
      
      // Инициализируем фильтры после загрузки изображения
      initFilters();
    });
  };
  reader.readAsDataURL(file);
}

// Сохранение отредактированного изображения
function saveImage() {
  if (!isImageLoaded) {
    showToast('Сначала загрузите изображение', 'error');
    return;
  }
  
  try {
    // Сохраняем текущее состояние объектов
    const json = canvas.toJSON();
    
    // Временно делаем все объекты невыделяемыми
    canvas.discardActiveObject();
    canvas.getObjects().forEach(obj => {
      obj.selectable = false;
    });
    canvas.renderAll();
    
    // Конвертируем канвас в изображение
    const dataURL = canvas.toDataURL({
      format: 'jpeg',
      quality: 0.95,
      multiplier: 1.5 // Увеличиваем разрешение выходного изображения
    });
    
    // Восстанавливаем выделяемость объектов
    canvas.loadFromJSON(json, function() {
      canvas.renderAll();
    });
    
    // Преобразуем dataURL в Blob
    const binaryData = atob(dataURL.split(',')[1]);
    const array = [];
    for (let i = 0; i < binaryData.length; i++) {
      array.push(binaryData.charCodeAt(i));
    }
    const blob = new Blob([new Uint8Array(array)], {type: 'image/jpeg'});
    
    // Создаем FormData для отправки на сервер
    const formData = new FormData();
    formData.append('photo', blob, 'edited_photo.jpg');
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    // Отправляем на сервер
    fetch('/photo-upload', {
      method: 'POST',
      body: formData,
      headers: {
        'X-Requested-With': 'XMLHttpRequest' // Для распознавания AJAX-запроса на сервере
      }
    })
    .then(response => {
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      return response.json();
    })
    .then(data => {
      if (data.success) {
        // Создаем ссылку для скачивания изображения
        const link = document.createElement('a');
        link.href = data.path;
        link.download = data.filename;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // Показываем уведомление об успешном сохранении
        showToast('Изображение успешно сохранено!', 'success');
        
        // Переход назад после некоторой задержки
        setTimeout(() => {
          window.history.back();
        }, 1500);
      } else {
        showToast('Ошибка при сохранении изображения', 'error');
      }
    })
    .catch(error => {
      console.error('Ошибка при сохранении изображения:', error);
      showToast('Произошла ошибка при сохранении: ' + error.message, 'error');
    });
  } catch (error) {
    console.error('Ошибка при подготовке изображения:', error);
    showToast('Ошибка при подготовке изображения: ' + error.message, 'error');
  }
}
<?php /**PATH C:\OSPanel\domains\sert\resources\views/photo-editor/partials/js/_image.blade.php ENDPATH**/ ?>