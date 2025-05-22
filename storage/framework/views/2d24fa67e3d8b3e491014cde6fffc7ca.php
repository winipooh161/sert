// Функции для отключения режимов редактирования
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
<?php /**PATH C:\OSPanel\domains\sert\resources\views/photo-editor/partials/js/_drawing.blade.php ENDPATH**/ ?>