<script>
const canvas = new fabric.Canvas('canvas', {
  preserveObjectStacking: true,
  selection: true,
  includeDefaultValues: false
});

// Глобальные переменные состояния
let isDrawingMode = false;
let isInCropMode = false;
let isMaskMode = false;
let cropRect = null;
let videoStream = null;
let image, isImageLoaded = false;
let undoHistory = [], redoHistory = [];
let selectedFilter = 'normal';
let currentActiveTab = 'filters';
let activeTextObj = null;
let currentBrushColor = '#ffffff';
let currentBrushSize = 10;
let currentMaskShape = 'rectangle';
let drawingPaletteVisible = false;

// Расширенный список фильтров и их настройки
const filters = {
  normal: { name: 'Обычный', params: {} },
  vintage: { 
    name: 'Винтаж', 
    params: { brightness: -10, contrast: 10, sepia: 30, vignette: true }
  },
  sepia: { 
    name: 'Сепия', 
    params: { sepia: 60 } 
  },
  grayscale: { 
    name: 'Ч/Б', 
    params: { grayscale: 100 } 
  },
  lomo: { 
    name: 'Ломо', 
    params: { brightness: 5, contrast: 20, saturation: 30, vignette: true }
  },
  clarity: { 
    name: 'Четкость', 
    params: { contrast: 30, sharpen: 50 }
  },
  // Новые фильтры
  noir: {
    name: 'Нуар',
    params: { grayscale: 100, contrast: 40, brightness: -10, vignette: true }
  },
  aged: {
    name: 'Старое фото',
    params: { sepia: 80, noise: 30, vignette: true, contrast: -5 }
  },
  dramatic: {
    name: 'Драматик',
    params: { contrast: 50, brightness: -15, saturation: -10, vignette: true }
  },
  retro: {
    name: 'Ретро',
    params: { sepia: 30, hueRotate: 30, blur: 0.5 }
  },
  warm: {
    name: 'Теплый',
    params: { saturation: 20, temperature: 30 }
  },
  cool: {
    name: 'Холодный',
    params: { saturation: 10, temperature: -30 }
  },
  fresh: {
    name: 'Свежесть',
    params: { brightness: 10, contrast: 15, saturation: 20 }
  },
  soft: {
    name: 'Мягкий',
    params: { brightness: 5, contrast: -10, blur: 1 }
  },
  elegant: {
    name: 'Элегантный',
    params: { grayscale: 100, contrast: 15, brightness: 5 }
  },
  polaroid: {
    name: 'Полароид',
    params: { sepia: 20, brightness: 5, contrast: 10, border: true }
  },
  sunset: {
    name: 'Закат',
    params: { saturation: 30, temperature: 40, hueRotate: 10 }
  }
};

// Расширенный список стикеров
const stickers = [
  { icon: 'heart', color: '#ff6b6b', content: '<i class="fas fa-heart fa-2x"></i>' },
  { icon: 'star', color: '#ffd43b', content: '<i class="fas fa-star fa-2x"></i>' },
  { icon: 'smile', color: '#ffd43b', content: '<i class="fas fa-smile fa-2x"></i>' },
  { icon: 'fire', color: '#ff922b', content: '<i class="fas fa-fire fa-2x"></i>' },
  { icon: 'thumbs-up', color: '#74c0fc', content: '<i class="fas fa-thumbs-up fa-2x"></i>' },
  { icon: 'crown', color: '#ffd43b', content: '<i class="fas fa-crown fa-2x"></i>' },
  // Новые стикеры
  { icon: 'camera', color: '#20c997', content: '<i class="fas fa-camera fa-2x"></i>' },
  { icon: 'gift', color: '#ff6b6b', content: '<i class="fas fa-gift fa-2x"></i>' },
  { icon: 'sun', color: '#ffd43b', content: '<i class="fas fa-sun fa-2x"></i>' },
  { icon: 'moon', color: '#748ffc', content: '<i class="fas fa-moon fa-2x"></i>' },
  { icon: 'snowflake', color: '#74c0fc', content: '<i class="fas fa-snowflake fa-2x"></i>' },
  { icon: 'coffee', color: '#a98467', content: '<i class="fas fa-coffee fa-2x"></i>' },
  { icon: 'pizza', color: '#ff922b', content: '<i class="fas fa-pizza-slice fa-2x"></i>' },
  { icon: 'wine', color: '#9c36b5', content: '<i class="fas fa-wine-glass fa-2x"></i>' },
  { icon: 'car', color: '#22b8cf', content: '<i class="fas fa-car fa-2x"></i>' },
  { icon: 'plane', color: '#4dabf7', content: '<i class="fas fa-plane fa-2x"></i>' },
  { icon: 'music', color: '#e64980', content: '<i class="fas fa-music fa-2x"></i>' },
  { icon: 'gamepad', color: '#6741d9', content: '<i class="fas fa-gamepad fa-2x"></i>' },
  { icon: 'cat', color: '#ff922b', content: '<i class="fas fa-cat fa-2x"></i>' },
  { icon: 'dog', color: '#a98467', content: '<i class="fas fa-dog fa-2x"></i>' },
  { icon: 'cake', color: '#f06595', content: '<i class="fas fa-birthday-cake fa-2x"></i>' },
  { icon: 'rainbow', color: '#ff6b6b', content: '<i class="fas fa-rainbow fa-2x"></i>' },
  { icon: 'umbrella', color: '#4dabf7', content: '<i class="fas fa-umbrella-beach fa-2x"></i>' },
  { icon: 'graduation', color: '#ff922b', content: '<i class="fas fa-graduation-cap fa-2x"></i>' },
  { icon: 'trophy', color: '#ffd43b', content: '<i class="fas fa-trophy fa-2x"></i>' }
];

// История действий для Undo/Redo - исправленная версия
const history = {
  states: [],
  currentStateIndex: -1,
  maxStates: 30,
  
  saveState() {
    // Проверяем, если мы находимся не в конце истории, удаляем более поздние состояния
    if (this.currentStateIndex < this.states.length - 1) {
      this.states = this.states.slice(0, this.currentStateIndex + 1);
    }
    
    // Сериализуем текущее состояние канваса
    const json = canvas.toJSON(['selectable', 'hasControls', 'name']);
    const state = JSON.stringify(json);
    
    // Добавляем новое состояние
    this.states.push(state);
    
    // Если превысили лимит истории, удаляем первый элемент
    if (this.states.length > this.maxStates) {
      this.states.shift();
    }
    
    // Обновляем индекс текущего состояния
    this.currentStateIndex = this.states.length - 1;
    
    // Обновляем доступность кнопок Undo/Redo
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
        
        // Обновляем доступность кнопок Undo/Redo
        updateUndoRedoButtons();
      });
    }
  },
  
  // Инициализация первого состояния
  init() {
    this.saveState();
  }
};

// Обновление доступности кнопок Undo/Redo
function updateUndoRedoButtons() {
  const canUndo = history.currentStateIndex > 0;
  const canRedo = history.currentStateIndex < history.states.length - 1;
  
  document.getElementById('undo-btn').classList.toggle('disabled', !canUndo);
  document.getElementById('redo-btn').classList.toggle('disabled', !canRedo);
}

// Настройка событий для истории
canvas.on('object:added', () => history.saveState());
canvas.on('object:modified', () => history.saveState());
canvas.on('object:removed', () => history.saveState());

// Инициализация canvas при загрузке
setCanvasSize();
history.saveState();

// При изменении размера окна
window.addEventListener('resize', () => {
  setCanvasSize();
});

// Функция сохранения состояния для истории
function saveToHistory() {
  // Сохраняем текущее состояние канваса в историю
  undoHistory.push(JSON.stringify(canvas));
  
  // Очищаем историю redo при новом действии
  redoHistory = [];
  
  // Обновляем доступность кнопок отмены/повтора
  updateUndoRedoButtons();
}
</script><?php /**PATH C:\OSPanel\domains\sert\resources\views/photo-editor/partials/_scripts-init.blade.php ENDPATH**/ ?>