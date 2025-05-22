function exitCropMode() {
  if (!isInCropMode) return;
  
  // Удаляем объект рамки кадрирования, если он существует
  if (cropRect && canvas.contains(cropRect)) {
    canvas.remove(cropRect);
  }
  
  isInCropMode = false;
  cropRect = null;
  
  // Восстанавливаем выделяемость всех объектов
  canvas.getObjects().forEach(obj => {
    obj.selectable = true;
  });
  
  // Скрываем элементы управления обрезкой
  hideCropControls();
  
  // Сбрасываем активное состояние кнопки обрезки
  const cropButton = document.getElementById('crop-btn');
  if (cropButton) cropButton.classList.remove('active');
}

// Функция для отображения элементов управления обрезкой
function showCropControls() {
  // Проверяем, существуют ли уже элементы управления
  let cropControls = document.getElementById('crop-controls');
  
  if (!cropControls) {
    // Создаем элементы управления обрезкой
    cropControls = document.createElement('div');
    cropControls.id = 'crop-controls';
    cropControls.className = 'crop-controls';
    cropControls.innerHTML = `
      <button id="apply-crop" class="action-button accent">
        <i class="fas fa-check"></i> Применить
      </button>
      <button id="cancel-crop" class="action-button danger">
        <i class="fas fa-times"></i> Отмена
      </button>
    `;
    
    document.body.appendChild(cropControls);
    
    // Настройка обработчиков событий
    document.getElementById('apply-crop').addEventListener('click', applyCrop);
    document.getElementById('cancel-crop').addEventListener('click', exitCropMode);
  } else {
    cropControls.style.display = 'flex';
  }
}

// Функция для скрытия элементов управления обрезкой
function hideCropControls() {
  const cropControls = document.getElementById('crop-controls');
  if (cropControls) {
    cropControls.style.display = 'none';
  }
}

// Функция для начала режима кадрирования
function startCropMode() {
  if (!isImageLoaded) return;
  
  // Создаем прямоугольник для обрезки
  const canvasWidth = canvas.getWidth();
  const canvasHeight = canvas.getHeight();
  const cropWidth = canvasWidth * 0.8;
  const cropHeight = canvasHeight * 0.8;
  
  cropRect = new fabric.Rect({
    left: canvasWidth / 2 - cropWidth / 2,
    top: canvasHeight / 2 - cropHeight / 2,
    width: cropWidth,
    height: cropHeight,
    fill: 'rgba(0,0,0,0.3)',
    stroke: 'white',
    strokeWidth: 2,
    strokeDashArray: [5, 5],
    selectable: true,
    hasControls: true
  });
  
  canvas.add(cropRect);
  canvas.setActiveObject(cropRect);
  
  // Делаем остальные объекты невыделяемыми
  canvas.getObjects().forEach(obj => {
    if (obj !== cropRect) {
      obj.selectable = false;
    }
  });
  
  // Показываем элементы управления
  showCropControls();
}

// Функция применения обрезки
function applyCrop() {
  if (!isInCropMode || !cropRect) return;
  
  // Сохраняем состояние перед обрезкой
  saveToHistory();
  
  // Получаем размеры и позицию обрезки
  const rect = cropRect.getBoundingRect();
  
  // Получаем основное изображение
  const mainImage = canvas.getObjects().find(obj => obj.name === 'mainImage');
  if (!mainImage) {
    exitCropMode();
    return;
  }
  
  // Создаем новый объект с обрезанным изображением
  const croppedImage = new fabric.Image(mainImage.getElement(), {
    left: mainImage.left,
    top: mainImage.top,
    scaleX: mainImage.scaleX,
    scaleY: mainImage.scaleY,
    originX: mainImage.originX,
    originY: mainImage.originY,
    angle: mainImage.angle,
    flipX: mainImage.flipX,
    flipY: mainImage.flipY,
    name: 'mainImage'
  });
  
  // Устанавливаем обрезку
  croppedImage.clipPath = new fabric.Rect({
    left: rect.left - mainImage.left + mainImage.width / 2 * mainImage.scaleX,
    top: rect.top - mainImage.top + mainImage.height / 2 * mainImage.scaleY,
    width: rect.width,
    height: rect.height,
    originX: 'center',
    originY: 'center'
  });
  
  // Удаляем старое изображение и прямоугольник обрезки
  canvas.remove(mainImage);
  canvas.remove(cropRect);
  
  // Добавляем новое обрезанное изображение
  canvas.add(croppedImage);
  canvas.renderAll();
  
  // Выходим из режима обрезки
  exitCropMode();
  
  // Показываем уведомление
  showToast('Изображение успешно обрезано', 'success');
}
<?php /**PATH C:\OSPanel\domains\sert\resources\views/photo-editor/partials/js/_crop.blade.php ENDPATH**/ ?>