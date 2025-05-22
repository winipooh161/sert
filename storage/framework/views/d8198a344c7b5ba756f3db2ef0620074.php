// История действий для Undo/Redo
const history = {
  states: [],
  currentStateIndex: -1,
  maxStates: 30,
  
  saveState() {
    if (this.currentStateIndex < this.states.length - 1) {
      this.states = this.states.slice(0, this.currentStateIndex + 1);
    }
    
    const state = JSON.stringify(canvas.toJSON(['selectable', 'hasControls']));
    this.states.push(state);
    
    if (this.states.length > this.maxStates) {
      this.states.shift();
    }
    
    this.currentStateIndex = this.states.length - 1;
    updateUndoRedoButtons();
  },
  
  undo() {
    if (this.currentStateIndex > 0) {
      this.currentStateIndex--;
      this.loadState();
    }
  },
  
  redo() {
    if (this.currentStateIndex < this.states.length - 1) {
      this.currentStateIndex++;
      this.loadState();
    }
  },
  
  loadState() {
    if (this.states[this.currentStateIndex]) {
      canvas.loadFromJSON(this.states[this.currentStateIndex], () => {
        canvas.renderAll();
        updateUndoRedoButtons();
      });
    }
  }
};

// Функция сохранения состояния для истории
function saveToHistory() {
  // Сохраняем текущее состояние канваса в историю
  undoHistory.push(JSON.stringify(canvas));
  
  // Очищаем историю redo при новом действии
  redoHistory = [];
  
  // Обновляем доступность кнопок отмены/повтора
  updateUndoRedoButtons();
}

// Отмена действия
function undo() {
  if (undoHistory.length <= 1) return;
  
  // Сохраняем текущее состояние для возможности повтора
  redoHistory.push(undoHistory.pop());
  
  // Восстанавливаем предыдущее состояние
  const prevState = undoHistory[undoHistory.length - 1];
  canvas.loadFromJSON(prevState, function() {
    canvas.renderAll();
    updateUndoRedoButtons();
  });
}

// Повтор действия
function redo() {
  if (redoHistory.length === 0) return;
  
  // Восстанавливаем отмененное действие
  const nextState = redoHistory.pop();
  undoHistory.push(nextState);
  
  canvas.loadFromJSON(nextState, function() {
    canvas.renderAll();
    updateUndoRedoButtons();
  });
}
<?php /**PATH C:\OSPanel\domains\sert\resources\views/photo-editor/partials/js/_history.blade.php ENDPATH**/ ?>