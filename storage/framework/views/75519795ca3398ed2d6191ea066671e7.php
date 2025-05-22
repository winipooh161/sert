// Новая функция: Создание и отображение модального окна с камерой
function showCameraModal() {
  // Создаем модальное окно
  const modal = document.createElement('div');
  modal.className = 'editor-modal';
  modal.id = 'camera-modal';
  modal.style.display = 'flex';
  modal.style.flexDirection = 'column';
  modal.style.alignItems = 'center';
  modal.style.justifyContent = 'center';
  modal.style.zIndex = '1000';
  
  // Создаем контейнер для видео
  const videoContainer = document.createElement('div');
  videoContainer.style.width = '100%';
  videoContainer.style.maxWidth = '500px';
  videoContainer.style.position = 'relative';
  videoContainer.style.marginBottom = '20px';
  
  // Создаем индикатор загрузки
  const loadingIndicator = document.createElement('div');
  loadingIndicator.style.position = 'absolute';
  loadingIndicator.style.top = '50%';
  loadingIndicator.style.left = '50%';
  loadingIndicator.style.transform = 'translate(-50%, -50%)';
  loadingIndicator.style.textAlign = 'center';
  loadingIndicator.innerHTML = '<i class="fas fa-spinner fa-spin fa-3x"></i><p>Подключение к камере...</p>';
  
  // Создаем элемент видео
  const video = document.createElement('video');
  video.id = 'camera-feed';
  video.style.width = '100%';
  video.style.borderRadius = '8px';
  video.style.backgroundColor = '#000';
  video.autoplay = true;
  video.playsInline = true; // Важно для iOS
  video.style.display = 'none'; // Скрываем до загрузки
  
  // Создаем кнопки управления
  const controlsContainer = document.createElement('div');
  controlsContainer.style.display = 'flex';
  controlsContainer.style.justifyContent = 'space-around';
  controlsContainer.style.width = '100%';
  controlsContainer.style.maxWidth = '500px';
  
  // Кнопка отмены
  const cancelButton = document.createElement('button');
  cancelButton.className = 'action-button';
  cancelButton.innerHTML = '<i class="fas fa-times"></i> Отмена';
  cancelButton.style.marginRight = '10px';
  
  // Кнопка захвата
  const captureButton = document.createElement('button');
  captureButton.className = 'action-button accent';
  captureButton.innerHTML = '<i class="fas fa-camera"></i> Сделать фото';
  captureButton.style.minWidth = '180px';
  
  // Добавляем элементы в контейнер
  videoContainer.appendChild(loadingIndicator);
  videoContainer.appendChild(video);
  controlsContainer.appendChild(cancelButton);
  controlsContainer.appendChild(captureButton);
  modal.appendChild(videoContainer);
  modal.appendChild(controlsContainer);
  document.body.appendChild(modal);
  
  // Запрашиваем доступ к камере
  navigator.mediaDevices.getUserMedia({
    video: { facingMode: 'environment', width: { ideal: 1920 }, height: { ideal: 1080 } },
    audio: false
  })
  .then(stream => {
    video.srcObject = stream;
    loadingIndicator.style.display = 'none';
    video.style.display = 'block';
    
    // Вибрация для уведомления о подключении камеры
    if (window.navigator.vibrate) {
      window.navigator.vibrate(50);
    }
    
    // Обработчик для кнопки захвата
    captureButton.addEventListener('click', function() {
      try {
        // Создаем canvas для захвата кадра
        const captureCanvas = document.createElement('canvas');
        captureCanvas.width = video.videoWidth;
        captureCanvas.height = video.videoHeight;
        const ctx = captureCanvas.getContext('2d');
        ctx.drawImage(video, 0, 0, captureCanvas.width, captureCanvas.height);
        
        // Преобразуем изображение в файл
        captureCanvas.toBlob(function(blob) {
          const file = new File([blob], "camera-capture.jpg", { type: "image/jpeg" });
          
          // Останавливаем все видеотреки
          const tracks = stream.getTracks();
          tracks.forEach(track => {
            track.stop();
          });
          
          // Создаем имитацию выбора файла
          const dataTransfer = new DataTransfer();
          dataTransfer.items.add(file);
          const fileInput = document.getElementById('image-upload');
          fileInput.files = dataTransfer.files;
          
          // Вызываем событие change для загрузки изображения
          const event = new Event('change', { bubbles: true });
          fileInput.dispatchEvent(event);
          
          // Закрываем модальное окно
          modal.remove();
          
          // Явно скрываем плейсхолдер при захвате с камеры
          const placeholder = document.getElementById('upload-placeholder');
          if (placeholder) {
            placeholder.style.display = 'none';
            placeholder.style.zIndex = '-1';
            placeholder.classList.add('hidden'); // Добавляем класс для полного скрытия
          }
          
          // Устанавливаем флаг, что изображение загружено
          isImageLoaded = true;
        }, 'image/jpeg', 0.95);
      } catch (error) {
        console.error('Ошибка при захвате кадра:', error);
        showToast('Ошибка при захвате кадра', 'error');
      }
    });
    
    // Обработчик для кнопки отмены
    cancelButton.addEventListener('click', function() {
      // Останавливаем все видеотреки
      const tracks = stream.getTracks();
      tracks.forEach(track => {
        track.stop();
      });
      
      // Закрываем модальное окно
      modal.remove();
    });
  })
  .catch(error => {
    console.error('Ошибка доступа к камере: ', error);
    loadingIndicator.innerHTML = `
      <i class="fas fa-exclamation-triangle fa-3x text-warning"></i>
      <p>Не удалось получить доступ к камере</p>
      <small>${error.message}</small>
    `;
    
    // Скрываем кнопку захвата
    captureButton.style.display = 'none';
    
    // Меняем кнопку отмены на "Закрыть"
    cancelButton.innerHTML = '<i class="fas fa-times"></i> Закрыть';
    cancelButton.style.marginRight = '0';
    
    cancelButton.addEventListener('click', function() {
      modal.remove();
    });
  });
}
<?php /**PATH C:\OSPanel\domains\sert\resources\views/photo-editor/partials/js/_camera.blade.php ENDPATH**/ ?>