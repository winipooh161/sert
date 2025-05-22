// Определение функции initUI
function initUI() {
  // Активируем первую вкладку по умолчанию
  const defaultTab = document.querySelector('.tab-button[data-tab="filters"]');
  if (defaultTab) defaultTab.click();
  
  // Настройка обработчиков для кнопок загрузки
  document.getElementById('upload-image-btn')?.addEventListener('click', () => {
    document.getElementById('image-upload').click();
  });
  
  document.getElementById('capture-image-btn')?.addEventListener('click', showCameraModal);
  
  // Настройка обработчиков для кнопки добавления стикера
  document.getElementById('add-sticker-btn')?.addEventListener('click', () => {
    document.getElementById('sticker-upload').click();
  });
  
  // Обработчик для вставки из буфера обмена
  document.addEventListener('paste', function(e) {
    if (!e.clipboardData || !e.clipboardData.items) return;
    
    const items = e.clipboardData.items;
    for (let i = 0; i < items.length; i++) {
      if (items[i].type.indexOf('image') !== -1) {
        const blob = items[i].getAsFile();
        const reader = new FileReader();
        reader.onload = function(event) {
          loadImage(blob);
        };
        reader.readAsDataURL(blob);
        break;
      }
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
  
  // Инициализация слайдеров настройки
  document.getElementById('brightness-slider').addEventListener('input', function() {
    document.getElementById('brightness-value').textContent = this.value;
    applyAdjustment('brightness', this.value);
  });
  
  document.getElementById('contrast-slider').addEventListener('input', function() {
    document.getElementById('contrast-value').textContent = this.value;
    applyAdjustment('contrast', this.value);
  });
  
  document.getElementById('saturation-slider').addEventListener('input', function() {
    document.getElementById('saturation-value').textContent = this.value;
    applyAdjustment('saturation', this.value);
  });
  
  // Инициализация кнопок инструментов
  document.getElementById('undo-btn').addEventListener('click', undo);
  document.getElementById('redo-btn').addEventListener('click', redo);
  document.getElementById('reset-btn').addEventListener('click', resetEditor);
  
  // Загрузка пользовательского стикера
  document.getElementById('sticker-upload').addEventListener('change', function(e) {
    if (e.target.files && e.target.files[0]) {
      uploadUserSticker(e.target.files[0]);
    }
  });
  
  // Обработка текстового инструмента
  document.getElementById('text-btn').addEventListener('click', showTextEditor);
  document.getElementById('cancel-text-btn').addEventListener('click', hideTextEditor);
  document.getElementById('apply-text-btn').addEventListener('click', applyText);
  
  // Обработка кнопки сохранения
  document.querySelector('.save-btn').addEventListener('click', saveImage);
  
  // Обработка кнопки закрытия
  document.querySelector('.close-btn').addEventListener('click', function() {
    if (isImageLoaded) {
      if (confirm('Вы уверены, что хотите выйти без сохранения?')) {
        window.history.back();
      }
    } else {
      window.history.back();
    }
  });
  
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
    } else {
      // Выходим из режима обрезки
      exitCropMode();
    }
  });
  
  // Добавляем обработчик для кнопки маски
  document.getElementById('mask-btn')?.addEventListener('click', function() {
    // Переключаем режим маски
    isMaskMode = !isMaskMode;
    
    // Обновляем внешний вид кнопки
    this.classList.toggle('active', isMaskMode);
    
    if (isMaskMode) {
      // Отключаем режим рисования, если он был включен
      disableDrawingMode();
      // Выходим из режима обрезки, если он был включен
      exitCropMode();
      
      // Входим в режим маски
      startMaskMode();
    } else {
      // Выходим из режима маски
      exitMaskMode();
    }
  });
  
  // Обработчик для поворота изображения
  document.getElementById('rotate-btn')?.addEventListener('click', function() {
    if (!isImageLoaded) return;
    
    // Сохраняем состояние перед поворотом
    saveToHistory();
    
    // Получаем основное изображение
    const mainImage = canvas.getObjects().find(obj => obj.name === 'mainImage');
    if (mainImage) {
      // Поворачиваем изображение на 90 градусов по часовой стрелке
      mainImage.rotate((mainImage.angle || 0) + 90);
      canvas.renderAll();
      
      showToast('Изображение повернуто на 90°', 'success');
    }
  });
  
  // Загрузка изображения
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
            
            // Скрываем плейсхолдер
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
}
<?php /**PATH C:\OSPanel\domains\sert\resources\views/photo-editor/partials/js/_ui.blade.php ENDPATH**/ ?>