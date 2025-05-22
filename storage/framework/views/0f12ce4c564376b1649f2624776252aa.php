// Показать сообщение с обратной связью
function showToast(message, type = 'info') {
  // Удаляем старые уведомления
  const oldToasts = document.querySelectorAll('.toast');
  oldToasts.forEach(toast => {
    document.body.removeChild(toast);
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

// Обновление доступности кнопок Undo/Redo
function updateUndoRedoButtons() {
  const canUndo = history.currentStateIndex > 0;
  const canRedo = history.currentStateIndex < history.states.length - 1;
  
  document.getElementById('undo-btn').classList.toggle('disabled', !canUndo);
  document.getElementById('redo-btn').classList.toggle('disabled', !canRedo);
}

// Функция сброса редактора
function resetEditor() {
  if (!isImageLoaded) return;
  
  if (confirm('Вы уверены, что хотите сбросить все изменения?')) {
    // Очищаем холст
    canvas.clear();
    
    // Сбрасываем историю
    undoHistory = [];
    redoHistory = [];
    
    // Сбрасываем флаги
    isImageLoaded = false;
    
    // Показываем плейсхолдер
    const placeholder = document.getElementById('upload-placeholder');
    if (placeholder) {
      placeholder.style.display = 'flex';
      placeholder.style.zIndex = '5';
      placeholder.classList.remove('hidden');
    }
    
    // Уведомление
    showToast('Редактор сброшен', 'info');
  }
}
<?php /**PATH C:\OSPanel\domains\sert\resources\views/photo-editor/partials/js/_utils.blade.php ENDPATH**/ ?>