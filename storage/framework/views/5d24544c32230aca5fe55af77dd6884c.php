<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <title>Редактор фотографий</title>

  <!-- Cropper.js -->
  <link
    href="https://unpkg.com/cropperjs@^1.5.13/dist/cropper.min.css"
    rel="stylesheet"
  />
  <script src="https://unpkg.com/cropperjs@^1.5.13/dist/cropper.min.js"></script>

  <!-- CamanJS -->
  <script src="https://cdn.jsdelivr.net/npm/caman@4.1.2/dist/caman.full.min.js"></script>

  <style>
    body { margin:0; padding:20px; font-family: Arial, sans-serif; background-color: #f5f5f5; }
    #editor {
      max-width: 600px;
      margin: 1rem auto;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      border-radius: 8px;
      overflow: hidden;
      background-color: #fff;
      padding: 10px;
    }
    #editor img, #editor canvas {
      width: 100%;
      display: block;
      border-radius: 4px;
    }
    #controls {
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem;
      justify-content: center;
      margin: 1rem 0;
      padding: 15px;
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      max-width: 600px;
      margin: 1rem auto;
    }
    #controls button {
      padding: 0.5rem 1rem;
      font-size: 1rem;
      border: none;
      border-radius: 4px;
      background-color: #3498db;
      color: white;
      cursor: pointer;
      transition: background-color 0.2s;
    }
    #controls button:hover {
      background-color: #2980b9;
    }
    #controls input {
      padding: 0.5rem;
      width: 100%;
      max-width: 250px;
    }
    label {
      display: flex;
      flex-direction: column;
      width: 100%;
      max-width: 250px;
      margin: 5px 0;
    }
    .header {
      text-align: center;
      padding: 20px 0;
    }
    .upload-container {
      text-align: center;
      margin-bottom: 20px;
    }
    #upload-btn {
      padding: 10px 20px;
      background-color: #2ecc71;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    #upload-btn:hover {
      background-color: #27ae60;
    }
    #file-input {
      display: none;
    }
    #export-btn {
      background-color: #e74c3c;
    }
    #export-btn:hover {
      background-color: #c0392b;
    }
    .filter-group, .transform-group, .adjust-group {
      width: 100%;
      padding: 10px 0;
      border-bottom: 1px solid #eee;
      margin-bottom: 10px;
    }
    .group-title {
      width: 100%;
      font-weight: bold;
      margin-bottom: 10px;
      color: #333;
    }
  </style>
</head>
<body>

  <div class="header">
    <h1>Редактор фотографий</h1>
  </div>

  <div class="upload-container">
    <button id="upload-btn">Загрузить фото</button>
    <input type="file" id="file-input" accept="image/*">
  </div>

  <div id="editor">
    <img id="to-edit" src="<?php echo e(asset('images/placeholder.jpg')); ?>" alt="Загрузите фото">
  </div>

  <div id="controls">
    <div class="transform-group">
      <div class="group-title">Трансформация</div>
      <!-- Cropper -->
      <button id="crop-btn">Обрезать</button>
      <button id="reset-crop">Сбросить обрезку</button>
      <button id="rotate-left">↺ Повернуть</button>
      <button id="rotate-right">↻ Повернуть</button>
    </div>
    
    <div class="filter-group">
      <div class="group-title">Фильтры</div>
      <!-- Filters -->
      <button data-filter="vintage">Vintage</button>
      <button data-filter="lomo">Lomo</button>
      <button data-filter="greyscale">Черно-белый</button>
      <button data-filter="sepia">Сепия</button>
      <button data-filter="clarity">Четкость</button>
      <button data-filter="invert">Инверсия</button>
    </div>
    
    <div class="adjust-group">
      <div class="group-title">Настройки</div>
      <!-- Brightness/Contrast sliders -->
      <label>
        Яркость
        <input type="range" id="brightness" min="-100" max="100" value="0">
      </label>
      <label>
        Контраст
        <input type="range" id="contrast" min="-100" max="100" value="0">
      </label>
      <label>
        Насыщенность
        <input type="range" id="saturation" min="-100" max="100" value="0">
      </label>
    </div>
    
    <!-- Export -->
    <button id="export-btn">Сохранить</button>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      let cropper = null;
      const editorDiv = document.getElementById('editor');
      const image = document.getElementById('to-edit');
      
      // Загрузка фото
      document.getElementById('upload-btn').addEventListener('click', () => {
        document.getElementById('file-input').click();
      });
      
      document.getElementById('file-input').addEventListener('change', (e) => {
        if (e.target.files && e.target.files[0]) {
          const reader = new FileReader();
          reader.onload = function(event) {
            // Сбрасываем редактор
            editorDiv.innerHTML = '<img id="to-edit" src="' + event.target.result + '" alt="Фото">';
            
            // Инициализируем кроппер заново
            initCropper();
          };
          reader.readAsDataURL(e.target.files[0]);
        }
      });
      
      function initCropper() {
        const img = document.getElementById('to-edit');
        if (cropper) {
          cropper.destroy();
        }
        cropper = new Cropper(img, {
          viewMode: 1,
          autoCropArea: 0.8,
          responsive: true,
          background: false,
        });
      }
      
      // Инициализируем кроппер при загрузке страницы
      initCropper();

      // Кнопки обрезки
      document.getElementById('crop-btn').onclick = () => {
        const canvas = cropper.getCroppedCanvas();
        // Заменим <img> на <canvas> для CamanJS
        editorDiv.innerHTML = '';
        editorDiv.appendChild(canvas);
        // отключаем Cropper
        cropper.destroy();
        cropper = null;
      };
      
      document.getElementById('reset-crop').onclick = () => {
        if (cropper) {
          cropper.reset();
        } else {
          location.reload(); // для простоты
        }
      };
      
      // Поворот
      document.getElementById('rotate-left').onclick = () => {
        if (cropper) {
          cropper.rotate(-90);
        }
      };
      
      document.getElementById('rotate-right').onclick = () => {
        if (cropper) {
          cropper.rotate(90);
        }
      };

      // Применение фильтра
      document.querySelectorAll('[data-filter]').forEach(btn => {
        btn.onclick = () => {
          if (!document.querySelector('#editor canvas')) {
            alert('Сначала выполните обрезку!');
            return;
          }
          
          Caman('#editor canvas', function() {
            this.revert(false);
            this[btn.dataset.filter]().render();
          });
        };
      });

      // Слайдеры яркости/контраста/насыщенности
      ['brightness', 'contrast', 'saturation'].forEach(id => {
        document.getElementById(id).oninput = e => {
          if (!document.querySelector('#editor canvas')) {
            alert('Сначала выполните обрезку!');
            e.target.value = 0;
            return;
          }
          
          const val = parseInt(e.target.value, 10);
          Caman('#editor canvas', function() {
            this.revert(false);
            if (id === 'brightness') this.brightness(val);
            if (id === 'contrast') this.contrast(val);
            if (id === 'saturation') this.saturation(val);
            this.render();
          });
        };
      });

      // Экспорт
      document.getElementById('export-btn').onclick = () => {
        const canvas = document.querySelector('#editor canvas');
        if (!canvas) {
          alert('Сначала выполните обрезку!');
          return;
        }
        
        canvas.toBlob(blob => {
          const form = new FormData();
          form.append('photo', blob, 'edited.jpg');
          
          fetch('<?php echo e(route('photo.upload')); ?>', {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: form
          })
          .then(r => r.json())
          .then(j => {
            if (j.success) {
              alert('Фото сохранено: ' + j.path);
            } else {
              alert('Ошибка: ' + (j.error || 'Неизвестная ошибка'));
            }
          })
          .catch(err => {
            console.error(err);
            alert('Ошибка загрузки');
          });
        }, 'image/jpeg', 0.9);
      };
    });
  </script>
</body>
</html>
<?php /**PATH C:\OSPanel\domains\sert\resources\views/photo-editor.blade.php ENDPATH**/ ?>