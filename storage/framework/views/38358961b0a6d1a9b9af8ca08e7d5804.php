<script>
function disableDrawingMode() {
  if (!isDrawingMode) return;
  
  canvas.isDrawingMode = false;
  isDrawingMode = false;
  
  // Скрываем палитру рисования
  hideDrawingPalette();
  
  // Сбрасываем активное состояние кнопки рисования
  const drawButton = document.getElementById('draw-btn');
  if (drawButton) drawButton.classList.remove('active');
}

// Функция для отображения палитры рисования
function showDrawingPalette() {
  // Проверяем, существует ли уже палитра
  let drawingPalette = document.getElementById('drawing-palette');
  
  if (!drawingPalette) {
    // Создаем палитру рисования
    drawingPalette = document.createElement('div');
    drawingPalette.id = 'drawing-palette';
    drawingPalette.innerHTML = `
      <div class="drawing-tools">
        <div class="palette-title">Цвет кисти</div>
        <div class="color-palette">
          <div class="color-swatch" data-color="#ffffff" style="background-color: #ffffff;"></div>
          <div class="color-swatch" data-color="#000000" style="background-color: #000000;"></div>
          <div class="color-swatch" data-color="#ff0000" style="background-color: #ff0000;"></div>
          <div class="color-swatch" data-color="#00ff00" style="background-color: #00ff00;"></div>
          <div class="color-swatch" data-color="#0000ff" style="background-color: #0000ff;"></div>
          <div class="color-swatch" data-color="#ffff00" style="background-color: #ffff00;"></div>
          <div class="color-swatch" data-color="#ff00ff" style="background-color: #ff00ff;"></div>
          <div class="color-swatch" data-color="#00ffff" style="background-color: #00ffff;"></div>
        </div>
        
        <div class="brush-controls">
          <label>Размер кисти: <span id="brush-size-value">${currentBrushSize}</span>px</label>
          <input type="range" id="brush-size" min="1" max="50" value="${currentBrushSize}">
        </div>
        
        <button id="close-palette" class="action-button danger mt-2">
          <i class="fas fa-times"></i> Закрыть палитру
        </button>
      </div>
    `;
    
    document.body.appendChild(drawingPalette);
    
    // Настройка обработчиков событий для палитры
    initDrawingPaletteEvents();
  } else {
    drawingPalette.style.display = 'flex';
  }
  
  drawingPaletteVisible = true;
  
  // Задаем активный цвет
  const activeColorSwatch = drawingPalette.querySelector(`[data-color="${currentBrushColor}"]`);
  if (activeColorSwatch) {
    activeColorSwatch.classList.add('active');
  }
}

// Функция для скрытия палитры рисования
function hideDrawingPalette() {
  const drawingPalette = document.getElementById('drawing-palette');
  if (drawingPalette) {
    drawingPalette.style.display = 'none';
  }
  drawingPaletteVisible = false;
}

// Инициализация событий палитры рисования
function initDrawingPaletteEvents() {
  // Выбор цвета кисти
  document.querySelectorAll('.color-swatch').forEach(swatch => {
    swatch.addEventListener('click', function() {
      const color = this.getAttribute('data-color');
      currentBrushColor = color;
      
      // Удаление активного класса у всех цветов
      document.querySelectorAll('.color-swatch').forEach(s => s.classList.remove('active'));
      
      // Добавление активного класса к выбранному цвету
      this.classList.add('active');
      
      // Установка цвета кисти
      canvas.freeDrawingBrush.color = color;
    });
  });
  
  // Изменение размера кисти
  const brushSizeSlider = document.getElementById('brush-size');
  if (brushSizeSlider) {
    brushSizeSlider.addEventListener('input', function() {
      const size = parseInt(this.value);
      currentBrushSize = size;
      
      // Обновление отображения размера
      document.getElementById('brush-size-value').textContent = size;
      
      // Установка размера кисти
      canvas.freeDrawingBrush.width = size;
    });
  }
  
  // Закрытие палитры
  const closeButton = document.getElementById('close-palette');
  if (closeButton) {
    closeButton.addEventListener('click', hideDrawingPalette);
  }
}

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

// Функция для начала режима маски
function startMaskMode() {
  if (!isImageLoaded) return;
  
  isMaskMode = true;
  
  // Показываем элементы управления маской
  showMaskControls();
  
  // Делаем основное изображение невыделяемым
  const mainImage = canvas.getObjects().find(obj => obj.name === 'mainImage');
  if (mainImage) {
    mainImage.selectable = false;
  }
  
  // Добавляем обработчик для создания маски по клику на холст
  canvas.on('mouse:down', createMaskShape);
}

// Функция для выхода из режима маски
function exitMaskMode() {
  if (!isMaskMode) return;
  
  isMaskMode = false;
  
  // Скрываем элементы управления маской
  hideMaskControls();
  
  // Восстанавливаем выделяемость объектов
  canvas.getObjects().forEach(obj => {
    if (obj.name === 'mainImage') {
      obj.selectable = false; // Основное изображение по-прежнему не выделяемое
    } else {
      obj.selectable = true;
    }
  });
  
  // Удаляем обработчик создания маски
  canvas.off('mouse:down', createMaskShape);
  
  // Сбрасываем активное состояние кнопки маски
  const maskButton = document.getElementById('mask-btn');
  if (maskButton) maskButton.classList.remove('active');
}

// Функция для отображения элементов управления маской
function showMaskControls() {
  // Проверяем, существуют ли уже элементы управления
  let maskControls = document.getElementById('mask-controls');
  
  if (!maskControls) {
    // Создаем элементы управления маской
    maskControls = document.createElement('div');
    maskControls.id = 'mask-controls';
    maskControls.className = 'mask-controls';
    maskControls.innerHTML = `
      <div class="mask-shape-menu">
        <div class="mask-shape-option" data-shape="rectangle">
          <i class="fas fa-square"></i> Прямоугольник
        </div>
        <div class="mask-shape-option" data-shape="circle">
          <i class="fas fa-circle"></i> Круг
        </div>
        <div class="mask-shape-option" data-shape="triangle">
          <i class="fas fa-play"></i> Треугольник
        </div>
        <div class="mask-shape-option" data-shape="heart">
          <i class="fas fa-heart"></i> Сердце
        </div>
      </div>
      <button id="exit-mask-mode" class="action-button danger">
        <i class="fas fa-times"></i> Выйти
      </button>
    `;
    
    document.body.appendChild(maskControls);
    
    // Настройка обработчиков событий
    document.querySelectorAll('.mask-shape-option').forEach(option => {
      option.addEventListener('click', function() {
        currentMaskShape = this.getAttribute('data-shape');
        
        // Обновляем активное состояние
        document.querySelectorAll('.mask-shape-option').forEach(opt => opt.classList.remove('active'));
        this.classList.add('active');
      });
    });
    
    document.getElementById('exit-mask-mode').addEventListener('click', exitMaskMode);
  } else {
    maskControls.style.display = 'flex';
  }
  
  // Устанавливаем активную форму маски
  const activeShapeOption = maskControls.querySelector(`[data-shape="${currentMaskShape}"]`);
  if (activeShapeOption) {
    activeShapeOption.classList.add('active');
  }
}

// Функция для скрытия элементов управления маской
function hideMaskControls() {
  const maskControls = document.getElementById('mask-controls');
  if (maskControls) {
    maskControls.style.display = 'none';
  }
}

// Функция для создания формы маски по клику на холст
function createMaskShape(options) {
  if (!isMaskMode) return;
  
  const pointer = canvas.getPointer(options.e);
  let maskObj;
  
  // Создаем маску в зависимости от выбранной формы
  switch (currentMaskShape) {
    case 'rectangle':
      maskObj = new fabric.Rect({
        left: pointer.x,
        top: pointer.y,
        width: 100,
        height: 100,
        fill: 'rgba(0,0,0,0.5)',
        originX: 'center',
        originY: 'center'
      });
      break;
      
    case 'circle':
      maskObj = new fabric.Circle({
        left: pointer.x,
        top: pointer.y,
        radius: 50,
        fill: 'rgba(0,0,0,0.5)',
        originX: 'center',
        originY: 'center'
      });
      break;
      
    case 'triangle':
      maskObj = new fabric.Triangle({
        left: pointer.x,
        top: pointer.y,
        width: 100,
        height: 100,
        fill: 'rgba(0,0,0,0.5)',
        originX: 'center',
        originY: 'center'
      });
      break;
      
    case 'heart':
      const heartPath = 'M 272.70141,238.71731 C 206.46141,238.71731 152.70146,292.4773 152.70146,358.71731 C 152.70146,493.47282 272.70145,563.52337 272.70145,563.52337 C 272.70145,563.52337 392.70145,483.47282 392.70145,358.71731 C 392.70145,292.47731 338.94144,238.7173 272.70144,238.71731 C 272.70143,238.71731 272.70142,238.71731 272.70141,238.71731 z';
      maskObj = new fabric.Path(heartPath, {
        left: pointer.x,
        top: pointer.y,
        fill: 'rgba(0,0,0,0.5)',
        scaleX: 0.2,
        scaleY: 0.2,
        originX: 'center',
        originY: 'center'
      });
      break;
  }
  
  if (maskObj) {
    // Добавляем свойства для маски
    maskObj.set({
      name: 'mask',
      transparentCorners: false,
      cornerColor: 'white',
      cornerStrokeColor: 'black',
      borderColor: 'black',
      cornerSize: 10
    });
    
    // Добавляем маску на холст
    canvas.add(maskObj);
    canvas.setActiveObject(maskObj);
    canvas.renderAll();
    
    // Применяем маску к основному изображению
    applyMaskToImage(maskObj);
  }
}

// Функция для применения маски к изображению
function applyMaskToImage(maskObj) {
  const mainImage = canvas.getObjects().find(obj => obj.name === 'mainImage');
  if (!mainImage) return;
  
  // Сохраняем состояние перед применением маски
  saveToHistory();
  
  // Масштабируем и позиционируем маску относительно изображения
  const scaledMask = fabric.util.object.clone(maskObj);
  scaledMask.set({
    left: maskObj.left - mainImage.left,
    top: maskObj.top - mainImage.top,
    originX: 'center',
    originY: 'center'
  });
  
  // Устанавливаем маску как клип-путь для изображения
  mainImage.clipPath = scaledMask;
  
  // Удаляем объект маски с холста
  canvas.remove(maskObj);
  
  canvas.renderAll();
  
  // Выходим из режима маски
  exitMaskMode();
  
  // Показываем уведомление
  showToast('Маска применена к изображению', 'success');
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
}

// Функция поворота изображения
function rotateImage(direction = 'right') {
  if (!isImageLoaded) return;
  
  // Сохраняем состояние перед поворотом
  saveToHistory();
  
  // Находим основное изображение
  const mainImage = canvas.getObjects().find(obj => obj.name === 'mainImage');
  
  if (!mainImage) {
    const bgImage = canvas.backgroundImage;
    if (!bgImage) return;
    
    // Вычисляем угол поворота
    const angle = direction === 'right' ? 90 : -90;
    
    // Сохраняем текущий угол и добавляем поворот
    let newAngle = (bgImage.angle || 0) + angle;
    
    // Поворачиваем фоновое изображение
    bgImage.rotate(newAngle);
    
    // Если угол кратен 180, возможно, требуется корректировка размеров
    if (newAngle % 180 === 0) {
      bgImage.scaleToWidth(canvas.getWidth());
    } else {
      // Для углов 90 и 270 меняем местами высоту и ширину
      bgImage.scaleToHeight(canvas.getWidth());
    }
    
    canvas.centerObject(bgImage);
  } else {
    // Вычисляем угол поворота
    const angle = direction === 'right' ? 90 : -90;
    
    // Сохраняем текущий угол и добавляем поворот
    mainImage.rotate((mainImage.angle || 0) + angle);
    
    // Корректируем положение
    canvas.centerObject(mainImage);
  }
  
  canvas.renderAll();
  showToast('Изображение повернуто', 'success');
}
</script><?php /**PATH C:\OSPanel\domains\sert\resources\views/photo-editor/partials/_scripts-modes.blade.php ENDPATH**/ ?>