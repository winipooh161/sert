<script>
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

// Инициализация интерфейса и обработчиков событий
function initUI() {
  // Активируем первую вкладку по умолчанию
  const defaultTab = document.querySelector('.tab-button[data-tab="filters"]');
  if (defaultTab) defaultTab.click();
  
  // Привязываем обработчики к кнопкам инструментов
  document.getElementById('rotate-btn')?.addEventListener('click', function() {
    rotateImage('right');
  });
  
  document.getElementById('mask-btn')?.addEventListener('click', function() {
    // Переключаем режим маски
    isMaskMode = !isMaskMode;
    
    // Обновляем внешний вид кнопки
    this.classList.toggle('active', isMaskMode);
    
    if (isMaskMode) {
      // Отключаем другие режимы
      disableDrawingMode();
      exitCropMode();
      
      // Запускаем режим маски
      startMaskMode();
    } else {
      // Выходим из режима маски
      exitMaskMode();
    }
  });
  
  // Привязываем обработчики для кнопок отмены/повтора
  document.getElementById('undo-btn')?.addEventListener('click', undo);
  document.getElementById('redo-btn')?.addEventListener('click', redo);
  
  // Привязываем обработчик для кнопки сброса
  document.getElementById('reset-btn')?.addEventListener('click', resetEditor);
  
  // Остальные обработчики событий
  // ...existing code...
}

// Функция сброса редактора
function resetEditor() {
  if (!isImageLoaded) return;
  
  if (confirm('Вы уверены, что хотите сбросить все изменения?')) {
    // Сохраняем текущее состояние для истории
    saveToHistory();
    
    // Очищаем канвас
    canvas.clear();
    
    // Получаем первичное изображение из истории
    if (undoHistory.length > 0) {
      const initialState = undoHistory[0];
      canvas.loadFromJSON(initialState, function() {
        canvas.renderAll();
      });
    }
    
    // Показываем уведомление
    showToast('Редактор сброшен к исходному состоянию', 'info');
  }
}

// Отмена действия - исправленная версия
function undo() {
  history.undo();
  showToast('Действие отменено', 'info');
}

// Повтор действия - исправленная версия
function redo() {
  history.redo();
  showToast('Действие восстановлено', 'info');
}

// Показ текстового редактора
function showTextEditor() {
  document.getElementById('text-editor').style.display = 'flex';
  document.getElementById('text-input').focus();
}

// Скрытие текстового редактора
function hideTextEditor() {
  document.getElementById('text-editor').style.display = 'none';
  document.getElementById('text-input').value = '';
}

// Применение текста на изображение
function applyText() {
  if (!isImageLoaded) return;
  
  const textValue = document.getElementById('text-input').value;
  if (!textValue) {
    hideTextEditor();
    return;
  }
  
  // Сохраняем состояние перед добавлением текста
  saveToHistory();
  
  // Получаем настройки текста
  const fontFamily = document.getElementById('font-family').value;
  const isBold = document.getElementById('text-bold-btn').classList.contains('active');
  const isItalic = document.getElementById('text-italic-btn').classList.contains('active');
  
  // Находим выбранный цвет
  let textColor = '#ffffff';
  document.querySelectorAll('.color-option').forEach(option => {
    if (option.classList.contains('active')) {
      textColor = option.getAttribute('data-color');
    }
  });
  
  // Создаем текстовый объект (исправлен textBaseline, удален invalid параметр)
  const textObj = new fabric.Text(textValue, {
    left: canvas.getWidth() / 2,
    top: canvas.getHeight() / 2,
    originX: 'center',
    originY: 'center',
    fontFamily: fontFamily,
    fill: textColor,
    fontWeight: isBold ? 'bold' : 'normal',
    fontStyle: isItalic ? 'italic' : 'normal',
    name: 'textObject'
  });
  
  canvas.add(textObj);
  canvas.setActiveObject(textObj);
  canvas.renderAll();
  
  hideTextEditor();
}

// Показать сообщение с обратной связью
function showToast(message, type = 'info') {
  // Удаляем старые уведомления
  const oldToasts = document.querySelectorAll('.toast');
  oldToasts.forEach(toast => {
    if (toast.parentNode) document.body.removeChild(toast);
  });
  
  // Создаем новое уведомление
  const toast = document.createElement('div');
  toast.className = `toast ${type}`;
  toast.textContent = message;
  document.body.appendChild(toast);
  
  // Анимация появления
  setTimeout(() => {
    toast.style.opacity = 1;
  }, 10);
  
  // Автоматическое скрытие
  setTimeout(() => {
    toast.style.opacity = 0;
    setTimeout(() => {
      if (toast.parentNode) {
        document.body.removeChild(toast);
      }
    }, 300);
  }, 3000);
}

// Новая функция: Создание и отображение модального окна с камерой
function showCameraModal() {
  // Создаем модальное окно
  const modal = document.createElement('div');
  modal.className = 'editor-modal';
  modal.id = 'camera-modal';
  modal.style.display = 'flex';
  modal.style.flexDirection = 'column';
  modal.style.alignItems = 'center';
  modal.style.justifyContent = 'center';
  modal.style.zIndex = '1000';
  
  // Создаем контейнер для видео
  const videoContainer = document.createElement('div');
  videoContainer.style.width = '100%';
  videoContainer.style.maxWidth = '500px';
  videoContainer.style.position = 'relative';
  videoContainer.style.marginBottom = '20px';
  
  // Создаем индикатор загрузки
  const loadingIndicator = document.createElement('div');
  loadingIndicator.style.position = 'absolute';
  loadingIndicator.style.top = '50%';
  loadingIndicator.style.left = '50%';
  loadingIndicator.style.transform = 'translate(-50%, -50%)';
  loadingIndicator.style.textAlign = 'center';
  loadingIndicator.innerHTML = '<i class="fas fa-spinner fa-spin fa-3x"></i><p>Подключение к камере...</p>';
  
  // Создаем элемент видео
  const video = document.createElement('video');
  video.id = 'camera-feed';
  video.style.width = '100%';
  video.style.borderRadius = '8px';
  video.style.backgroundColor = '#000';
  video.autoplay = true;
  video.playsInline = true; // Важно для iOS
  video.style.display = 'none'; // Скрываем до загрузки
  
  // Создаем кнопки управления
  const controlsContainer = document.createElement('div');
  controlsContainer.style.display = 'flex';
  controlsContainer.style.justifyContent = 'space-around';
  controlsContainer.style.width = '100%';
  controlsContainer.style.maxWidth = '500px';
  
  // Кнопка отмены
  const cancelButton = document.createElement('button');
  cancelButton.className = 'action-button';
  cancelButton.innerHTML = '<i class="fas fa-times"></i> Отмена';
  cancelButton.style.marginRight = '10px';
  
  // Кнопка захвата
  const captureButton = document.createElement('button');
  captureButton.className = 'action-button accent';
  captureButton.innerHTML = '<i class="fas fa-camera"></i> Сделать фото';
  captureButton.style.minWidth = '180px';
  
  // Добавляем элементы в контейнер
  videoContainer.appendChild(loadingIndicator);
  videoContainer.appendChild(video);
  controlsContainer.appendChild(cancelButton);
  controlsContainer.appendChild(captureButton);
  modal.appendChild(videoContainer);
  modal.appendChild(controlsContainer);
  document.body.appendChild(modal);
  
  // Запрашиваем доступ к камере
  navigator.mediaDevices.getUserMedia({
    video: { facingMode: 'environment', width: { ideal: 1920 }, height: { ideal: 1080 } },
    audio: false
  })
  .then(stream => {
    video.srcObject = stream;
    loadingIndicator.style.display = 'none';
    video.style.display = 'block';
    
    // Вибрация для уведомления о подключении камеры
    if (window.navigator.vibrate) {
      window.navigator.vibrate(50);
    }
    
    // Обработчик для кнопки захвата
    captureButton.addEventListener('click', function() {
      try {
        // Создаем canvas для захвата кадра
        const captureCanvas = document.createElement('canvas');
        captureCanvas.width = video.videoWidth;
        captureCanvas.height = video.videoHeight;
        const ctx = captureCanvas.getContext('2d');
        ctx.drawImage(video, 0, 0, captureCanvas.width, captureCanvas.height);
        
        // Преобразуем изображение в файл
        captureCanvas.toBlob(function(blob) {
          const file = new File([blob], "camera-capture.jpg", { type: "image/jpeg" });
          
          // Останавливаем все видеотреки
          const tracks = stream.getTracks();
          tracks.forEach(track => {
            track.stop();
          });
          
          // Создаем имитацию выбора файла
          const dataTransfer = new DataTransfer();
          dataTransfer.items.add(file);
          const fileInput = document.getElementById('image-upload');
          fileInput.files = dataTransfer.files;
          
          // Вызываем событие change для загрузки изображения
          const event = new Event('change', { bubbles: true });
          fileInput.dispatchEvent(event);
          
          // Закрываем модальное окно
          modal.remove();
          
          // Явно скрываем плейсхолдер при захвате с камеры
          const placeholder = document.getElementById('upload-placeholder');
          if (placeholder) {
            placeholder.style.display = 'none';
            placeholder.style.zIndex = '-1';
            placeholder.classList.add('hidden'); // Добавляем класс для полного скрытия
          }
          
          // Устанавливаем флаг, что изображение загружено
          isImageLoaded = true;
        }, 'image/jpeg', 0.95);
      } catch (error) {
        console.error('Ошибка при захвате кадра:', error);
        showToast('Ошибка при захвате кадра', 'error');
      }
    });
    
    // Обработчик для кнопки отмены
    cancelButton.addEventListener('click', function() {
      // Останавливаем все видеотреки
      const tracks = stream.getTracks();
      tracks.forEach(track => {
        track.stop();
      });
      
      // Закрываем модальное окно
      modal.remove();
    });
  })
  .catch(error => {
    console.error('Ошибка доступа к камере: ', error);
    loadingIndicator.innerHTML = `
      <i class="fas fa-exclamation-triangle fa-3x text-warning"></i>
      <p>Не удалось получить доступ к камере</p>
      <small>${error.message}</small>
    `;
    
    // Скрываем кнопку захвата
    captureButton.style.display = 'none';
    
    // Меняем кнопку отмены на "Закрыть"
    cancelButton.innerHTML = '<i class="fas fa-times"></i> Закрыть';
    cancelButton.style.marginRight = '0';
    
    cancelButton.addEventListener('click', function() {
      modal.remove();
    });
  });
}

// Инициализация обработчиков событий после DOMContentLoaded
document.addEventListener('DOMContentLoaded', function() {
  // Загрузка изображения
  document.getElementById('upload-image-btn').addEventListener('click', () => {
    document.getElementById('image-upload').click();
  });
  
  // Модифицированная функция загрузки изображения для обработки ошибок
  document.getElementById('image-upload').addEventListener('change', (e) => {
    if (e.target.files && e.target.files[0]) {
      const file = e.target.files[0];
      
      // Проверка размера файла (ограничение 20МБ)
      if (file.size > 20 * 1024 * 1024) {
        showToast('Файл слишком большой. Максимальный размер 20МБ', 'error');
        return;
      }
      
      // Проверка типа файла
      const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
      if (!validTypes.includes(file.type)) {
        showToast('Неподдерживаемый формат файла. Используйте JPEG, PNG или GIF', 'error');
        return;
      }
      
      const reader = new FileReader();
      reader.onload = function(event) {
        // Создаем объект изображения для проверки размеров
        const img = new Image();
        img.onload = function() {
          // Проверка минимального размера изображения
          if (img.width < 200 || img.height < 200) {
            showToast('Изображение слишком маленькое. Минимальный размер 200x200 пикселей', 'error');
            return;
          }
          
          // Если все проверки пройдены, загружаем изображение на канвас
          fabric.Image.fromURL(event.target.result, (imgObj) => {
            // Устанавливаем изображение как фон
            canvas.setBackgroundImage(imgObj, canvas.renderAll.bind(canvas));
            
            // Центрируем изображение
            centerBackgroundImage();
            
            // Скрываем плейсхолдер - ИСПРАВЛЕНО
            const placeholder = document.getElementById('upload-placeholder');
            if (placeholder) {
              placeholder.style.display = 'none';
              placeholder.style.zIndex = '-1';
              placeholder.classList.add('hidden'); // Добавляем класс для полного скрытия
            }
            
            // Устанавливаем флаг, что изображение загружено
            isImageLoaded = true;
            
            // Показываем сообщение
            showToast('Изображение загружено', 'success');
            
            // Сохраняем состояние после загрузки изображения
            history.saveState();
          });
        };
        img.src = event.target.result;
      };
      
      reader.onerror = function() {
        showToast('Ошибка при чтении файла', 'error');
      };
      
      reader.readAsDataURL(file);
    }
  });
  
  // Управление вкладками
  document.querySelectorAll('.tab-button').forEach(tab => {
    tab.addEventListener('click', () => {
      // Убираем активный класс у всех вкладок
      document.querySelectorAll('.tab-button').forEach(t => {
        t.classList.remove('active');
      });
      
      // Скрываем содержимое всех вкладок
      document.querySelectorAll('.toolbar-content').forEach(content => {
        content.style.display = 'none';
      });
      
      // Активируем выбранную вкладку
      tab.classList.add('active');
      
      // Показываем содержимое выбранной вкладки
      const tabId = tab.getAttribute('data-tab');
      document.getElementById(`${tabId}-tab`).style.display = 'block';
      
      // При переключении вкладок выключаем режимы
      disableDrawingMode();
      exitCropMode();
    });
  });
  
  // Кнопка захвата с камеры
  document.getElementById('capture-image-btn')?.addEventListener('click', function() {
    showCameraModal();
  });
  
  // Привязка событий клика к кнопкам стилей текста
  initTextFormatButtons();
  
  // Добавляем обработчик для кнопки рисования
  document.getElementById('draw-btn')?.addEventListener('click', function() {
    // Переключаем режим рисования
    isDrawingMode = !isDrawingMode;
    canvas.isDrawingMode = isDrawingMode;
    
    // Обновляем внешний вид кнопки
    this.classList.toggle('active', isDrawingMode);
    
    // Если включили режим рисования, отключаем режим кадрирования
    if (isDrawingMode) {
      exitCropMode();
      // Показываем палитру рисования
      showDrawingPalette();
    } else {
      // Выключаем режим рисования
      disableDrawingMode();
    }
  });
  
  // Добавляем обработчик для кнопки обрезки
  document.getElementById('crop-btn')?.addEventListener('click', function() {
    // Переключаем режим обрезки
    isInCropMode = !isInCropMode;
    
    // Обновляем внешний вид кнопки
    this.classList.toggle('active', isInCropMode);
    
    if (isInCropMode) {
      // Отключаем режим рисования, если он был включен
      disableDrawingMode();
      
      // Создаем рамку для обрезки
      startCropMode();
      
      // Показываем элементы управления обрезкой
      showCropControls();
    } else {
      // Выходим из режима обрезки
      exitCropMode();
    }
  });
  
  // Обработка текстового инструмента
  document.getElementById('text-btn')?.addEventListener('click', showTextEditor);
  document.getElementById('cancel-text-btn')?.addEventListener('click', hideTextEditor);
  document.getElementById('apply-text-btn')?.addEventListener('click', applyText);
  
  // Обработка кнопки сохранения
  document.querySelector('.save-btn')?.addEventListener('click', saveImage);
});

// Привязка событий клика к кнопкам стилей текста
function initTextFormatButtons() {
  document.getElementById('text-bold-btn')?.addEventListener('click', function() {
    this.classList.toggle('active');
  });
  
  document.getElementById('text-italic-btn')?.addEventListener('click', function() {
    this.classList.toggle('active');
  });
  
  // Обработка выбора цвета
  document.querySelectorAll('.color-option').forEach(option => {
    option.addEventListener('click', function() {
      document.querySelectorAll('.color-option').forEach(opt => {
        opt.classList.remove('active');
      });
      this.classList.add('active');
    });
  });
}

// Инициализация стикеров
function initStickers() {
  // Привязываем событие клика к стикерам
  document.querySelectorAll('.sticker-item').forEach(sticker => {
    if (sticker.id !== 'add-sticker-btn') { // Исключаем кнопку добавления
      sticker.addEventListener('click', function() {
        const icon = this.getAttribute('data-icon');
        const matchingSticker = stickers.find(s => s.icon === icon);
        if (matchingSticker) {
          addSticker(matchingSticker);
        }
      });
    }
  });

  // Загрузка пользовательского стикера при клике на кнопку
  document.getElementById('add-sticker-btn')?.addEventListener('click', function() {
    document.getElementById('sticker-upload').click();
  });

  // Обработчик для загрузки пользовательского стикера
  document.getElementById('sticker-upload')?.addEventListener('change', function(e) {
    if (e.target.files && e.target.files[0]) {
      uploadUserSticker(e.target.files[0]);
    }
  });
}

// Функция для добавления стикера на холст
function addSticker(stickerData) {
  if (!isImageLoaded) {
    showToast('Сначала загрузите изображение', 'error');
    return;
  }

  // Сохраняем состояние перед добавлением стикера
  saveToHistory();

  // Создаем HTML элемент для стикера
  const div = document.createElement('div');
  div.innerHTML = stickerData.content;
  const tempElement = div.firstChild;

  // Создаем SVG из HTML иконки
  fabric.loadSVGFromString(tempElement.outerHTML, function(objects, options) {
    const svgGroup = fabric.util.groupSVGElements(objects, options);
    
    // Масштабируем и настраиваем стикер
    svgGroup.set({
      left: canvas.getWidth() / 2,
      top: canvas.getHeight() / 2,
      originX: 'center',
      originY: 'center',
      fill: stickerData.color,
      scaleX: 1.5,
      scaleY: 1.5,
      name: 'sticker'
    });

    canvas.add(svgGroup);
    canvas.setActiveObject(svgGroup);
    canvas.renderAll();
    
    showToast('Стикер добавлен', 'success');
  });
}
</script><?php /**PATH C:\OSPanel\domains\sert\resources\views/photo-editor/partials/_scripts-utils.blade.php ENDPATH**/ ?>