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
  
  // Создаем текстовый объект
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

// Привязка событий клика к кнопкам стилей текста
function initTextFormatButtons() {
  document.getElementById('text-bold-btn').addEventListener('click', function() {
    this.classList.toggle('active');
  });
  
  document.getElementById('text-italic-btn').addEventListener('click', function() {
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
<?php /**PATH C:\OSPanel\domains\sert\resources\views/photo-editor/partials/js/_text.blade.php ENDPATH**/ ?>