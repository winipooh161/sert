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
<?php /**PATH C:\OSPanel\domains\sert\resources\views/photo-editor/partials/js/_mask.blade.php ENDPATH**/ ?>