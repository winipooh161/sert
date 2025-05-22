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

// Применение настроек яркости, контрастности, насыщенности
function applyAdjustment(type, value) {
  if (!isImageLoaded) return;
  
  // Получаем основное изображение
  const mainImage = canvas.getObjects().find(obj => obj.name === 'mainImage');
  if (!mainImage) return;
  
  // Сохраняем текущие фильтры или создаем новый массив
  let filters = mainImage.filters || [];
  
  // Находим индекс существующего фильтра или -1, если не найден
  let filterIndex = -1;
  
  switch (type) {
    case 'brightness':
      filterIndex = filters.findIndex(f => f instanceof fabric.Image.filters.Brightness);
      if (filterIndex >= 0) {
        filters[filterIndex].brightness = value / 100;
      } else {
        filters.push(new fabric.Image.filters.Brightness({ brightness: value / 100 }));
      }
      break;
    case 'contrast':
      filterIndex = filters.findIndex(f => f instanceof fabric.Image.filters.Contrast);
      if (filterIndex >= 0) {
        filters[filterIndex].contrast = value / 100;
      } else {
        filters.push(new fabric.Image.filters.Contrast({ contrast: value / 100 }));
      }
      break;
    case 'saturation':
      filterIndex = filters.findIndex(f => f instanceof fabric.Image.filters.Saturation);
      if (filterIndex >= 0) {
        filters[filterIndex].saturation = value / 100;
      } else {
        filters.push(new fabric.Image.filters.Saturation({ saturation: value / 100 }));
      }
      break;
  }
  
  mainImage.filters = filters;
  mainImage.applyFilters();
  canvas.renderAll();
}

// Генерация элементов интерфейса для фильтров
function initFilters() {
  const filterTab = document.getElementById('filters-tab');
  const filterScroller = filterTab.querySelector('.filter-scroller');
  filterScroller.innerHTML = ''; // Очищаем текущие фильтры
  
  // Добавляем все фильтры из объекта filters
  Object.keys(filters).forEach(filterKey => {
    const filter = filters[filterKey];
    const filterItem = document.createElement('div');
    filterItem.className = 'filter-item';
    filterItem.setAttribute('data-filter', filterKey);
    
    let bgColor = '#f0f0f0'; // Цвет по умолчанию
    // Определяем цвет в зависимости от фильтра
    switch (filterKey) {
      case 'vintage': bgColor = '#d3b38d'; break;
      case 'sepia': bgColor = '#a0826c'; break;
      case 'grayscale': bgColor = '#808080'; break;
      case 'lomo': bgColor = '#4d7ea8'; break;
      case 'clarity': bgColor = '#6b9ac4'; break;
      case 'noir': bgColor = '#333333'; break;
      case 'warm': bgColor = '#ff9966'; break;
      case 'cool': bgColor = '#6699cc'; break;
      // Можно добавить цвета для других фильтров
    }
    
    filterItem.innerHTML = `
      <div class="filter-preview" style="background-color: ${bgColor};">
        <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center;">
          <i class="fas fa-image"></i>
        </div>
      </div>
      <div class="filter-name">${filter.name}</div>
    `;
    
    filterItem.addEventListener('click', () => {
      applyFilterToImage(filterKey);
    });
    
    filterScroller.appendChild(filterItem);
  });
}

// Функция для применения фильтра к изображению
function applyFilterToImage(filterName) {
  if (!isImageLoaded) return;
  
  // Получаем основное изображение
  const mainImage = canvas.getObjects().find(obj => obj.name === 'mainImage');
  if (!mainImage) return;
  
  // Получаем параметры фильтра
  const filterParams = filters[filterName].params;
  
  // Сохраняем текущее состояние
  saveToHistory();
  
  // Сбрасываем все существующие фильтры
  mainImage.filters = [];
  
  // Добавляем новые фильтры в зависимости от параметров
  if (filterParams.brightness !== undefined) {
    mainImage.filters.push(new fabric.Image.filters.Brightness({
      brightness: filterParams.brightness / 100
    }));
  }
  
  if (filterParams.contrast !== undefined) {
    mainImage.filters.push(new fabric.Image.filters.Contrast({
      contrast: filterParams.contrast / 100
    }));
  }
  
  if (filterParams.saturation !== undefined) {
    mainImage.filters.push(new fabric.Image.filters.Saturation({
      saturation: filterParams.saturation / 100
    }));
  }
  
  if (filterParams.grayscale) {
    mainImage.filters.push(new fabric.Image.filters.Grayscale());
  }
  
  if (filterParams.sepia) {
    mainImage.filters.push(new fabric.Image.filters.Sepia());
  }
  
  // Применяем фильтры
  mainImage.applyFilters();
  canvas.renderAll();
  
  // Обновляем выбранный фильтр
  selectedFilter = filterName;
  
  // Подсвечиваем выбранный фильтр в интерфейсе
  document.querySelectorAll('.filter-item').forEach(item => {
    item.classList.toggle('active', item.getAttribute('data-filter') === filterName);
  });
  
  showToast(`Применен фильтр: ${filters[filterName].name}`, 'success');
}
<?php /**PATH C:\OSPanel\domains\sert\resources\views/photo-editor/partials/js/_filters.blade.php ENDPATH**/ ?>