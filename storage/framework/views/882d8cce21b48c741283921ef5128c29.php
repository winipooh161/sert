// Инициализация Fabric.js канваса с адаптивным размером
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

// Функция для установки размера канваса
function setCanvasSize() {
  const container = document.querySelector('.canvas-container');
  if (!container) return;
  
  const containerWidth = container.clientWidth;
  const containerHeight = container.clientHeight;
  
  // Устанавливаем максимальные размеры для канваса
  let canvasWidth = Math.min(containerWidth, 1200);
  let canvasHeight = Math.min(containerHeight, 1200);
  
  // Если изображение загружено, адаптируем размер канваса под него
  if (isImageLoaded && image) {
    const imgRatio = image.width / image.height;
    const containerRatio = containerWidth / containerHeight;
    
    if (imgRatio > containerRatio) {
      canvasWidth = containerWidth;
      canvasHeight = canvasWidth / imgRatio;
    } else {
      canvasHeight = containerHeight;
      canvasWidth = canvasHeight * imgRatio;
    }
  }
  
  canvas.setWidth(canvasWidth);
  canvas.setHeight(canvasHeight);
  canvas.renderAll();
}

// Функция центрирования фонового изображения
function centerBackgroundImage() {
  const bg = canvas.backgroundImage;
  if (!bg) return;
  
  // Масштабируем изображение, чтобы оно заполняло канвас
  const canvasRatio = canvas.width / canvas.height;
  const imgRatio = bg.width / bg.height;
  
  let scaleX, scaleY;
  if (canvasRatio >= imgRatio) {
    scaleX = canvas.width / bg.width;
    scaleY = scaleX;
  } else {
    scaleY = canvas.height / bg.height;
    scaleX = scaleY;
  }
  
  bg.scaleX = scaleX;
  bg.scaleY = scaleY;
  
  bg.left = canvas.width / 2;
  bg.top = canvas.height / 2;
  bg.originX = 'center';
  bg.originY = 'center';
  
  canvas.renderAll();
}

// Функция создания Canvas
function initCanvas() {
  // Устанавливаем свойства canvas
  canvas.backgroundColor = '#000';
  
  // Адаптируем размер канваса под размер экрана
  function resizeCanvas() {
    const container = document.querySelector('.canvas-container');
    const containerWidth = container.clientWidth;
    const containerHeight = container.clientHeight;
    
    // Устанавливаем максимальные размеры для канваса
    let canvasWidth = Math.min(containerWidth, 1200);
    let canvasHeight = Math.min(containerHeight, 1200);
    
    // Если изображение загружено, адаптируем размер канваса под него
    if (isImageLoaded && image) {
      const imgRatio = image.width / image.height;
      const containerRatio = containerWidth / containerHeight;
      
      if (imgRatio > containerRatio) {
        // Изображение шире контейнера
        canvasWidth = containerWidth;
        canvasHeight = canvasWidth / imgRatio;
      } else {
        // Изображение выше контейнера
        canvasHeight = containerHeight;
        canvasWidth = canvasHeight * imgRatio;
      }
    }
    
    canvas.setWidth(canvasWidth);
    canvas.setHeight(canvasHeight);
    canvas.renderAll();
  }
  
  // Адаптируем канвас при изменении размера окна
  window.addEventListener('resize', resizeCanvas);
  resizeCanvas();
  
  // Сохраняем состояние для истории
  canvas.on('object:modified', function() {
    saveToHistory();
  });
}
<?php /**PATH C:\OSPanel\domains\sert\resources\views/photo-editor/partials/js/_core.blade.php ENDPATH**/ ?>