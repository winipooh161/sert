<style>
/* Основные стили для редактора сертификатов */
#sidebarMenu{
    display: none !important; /* Скрываем боковое меню на мобильных устройствах */
}

/* Улучшение для карточек на мобильных */
@media (max-width: 767.98px) {
   #sidebarMenu{
    display: block !important; /* Показываем боковое меню на мобильных устройствах */
}
}

/* Стили для карточек эффектов */
.effect-card {
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.effect-card.selected {
    border-color: var(--bs-primary);
    box-shadow: 0 0 10px rgba(var(--bs-primary-rgb), 0.3);
}

.particles-preview {
    font-size: 1.2rem;
    margin: 10px 0;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Анимация для предпросмотра */
@keyframes float-preview {
    0% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
    100% { transform: translateY(0); }
}

/* Анимация для выбранного эффекта */
.effect-card.selected .particles-preview {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

/* Стили для квиза на мобильных устройствах */
.certificate-quiz-container {
    border-radius: 1rem;
    overflow: hidden;
}

.quiz-step {
    display: none;
    padding: 0.5rem 0;
}

.quiz-step.active {
    display: block;
    animation: fadeIn 0.4s ease;
}

.quiz-step-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
}

.quiz-step-title {
    color: var(--bs-primary);
    margin-bottom: 0.5rem;
}

.quiz-progress {
    font-size: 0.85rem;
    color: #6c757d;
}

/* Улучшенные стили для выбора продолжительности */
.duration-btn {
    padding: 0.75rem 0.5rem;
    font-size: 0.9rem;
}

.duration-btn.active {
    background-color: var(--bs-primary);
    color: white;
    border-color: var(--bs-primary);
}

/* Стили для загрузки изображений */
.cover-upload-label, .logo-upload-label {
    position: relative;
    cursor: pointer;
    transition: all 0.3s ease;
}

.cover-upload-label {
    min-height: 180px;
    border: 2px dashed #dee2e6;
    border-radius: 0.5rem;
}

.cover-upload-label:hover {
    border-color: var(--bs-primary);
    background-color: rgba(var(--bs-primary-rgb), 0.05);
}

/* Улучшенные стили для кнопок навигации квиза */
.quiz-navigation .btn {
    padding: 0.75rem 1rem;
    font-weight: 500;
}

/* Анимации для перехода между шагами */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes slideInRight {
    from { opacity: 0; transform: translateX(30px); }
    to { opacity: 1; transform: translateX(0); }
}

@keyframes slideInLeft {
    from { opacity: 0; transform: translateX(-30px); }
    to { opacity: 1; transform: translateX(0); }
}

/* Дополнительные стили для адаптивности */
@media (max-width: 576px) {
    .quiz-step-icon {
        width: 50px;
        height: 50px;
    }
    
    .quiz-navigation .btn {
        padding: 0.6rem 0.8rem;
        font-size: 0.9rem;
    }
    
    .duration-btn {
        padding: 0.5rem 0.4rem;
        font-size: 0.8rem;
    }
}

/* Улучшенные стили для выбора быстрых значений */
.quick-values {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.5rem;
}

/* Для устранения ошибки отображения в Safari/iOS */
input[type="date"] {
    appearance: none;
    -webkit-appearance: none;
    line-height: 1.4;
    padding: 0.6rem 0.75rem;
}

/* Стили для мобильной шторки предпросмотра */
.mobile-preview-drawer {
    position: relative;
    background-color: #fff;
    border-radius: 0 0 1rem 1rem;
    transition: all 0.3s ease-in-out;
    overflow: hidden;
}

.drawer-handle {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 0.75rem;
    cursor: pointer;
    background-color: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
}

.drawer-indicator {
    width: 50px;
    height: 5px;
    margin-bottom: 0.5rem;
    display: flex;
    justify-content: center;
    align-items: center;
}

.drawer-indicator i {
    color: #6c757d;
    font-size: 1.2rem;
}

.drawer-title {
    font-weight: 600;
    color: #495057;
    font-size: 0.9rem;
}

.drawer-content {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.5s ease;
}

.drawer-content.expanded {
    max-height: 70vh;
}

.preview-card.drawer-expanded .drawer-icon-down {
    display: none;
}

.preview-card.drawer-expanded .drawer-icon-up {
    display: inline-block;
}

/* Улучшаем внешний вид iframe на мобильных устройствах */
.mobile-preview-drawer .certificate-preview-container {
    min-height: 350px;
    max-height: 65vh;
}

.mobile-preview-drawer .certificate-preview-wrapper {
    height: 100%;
    max-height: 60vh;
}

.mobile-preview-drawer iframe {
    width: 100%;
    height: 100%;
    min-height: 300px;
    max-height: 60vh;
}

/* Фиксируем отображение для шторки на разных устройствах */
@media (max-width: 991.98px) {
    .drawer-content.expanded {
        max-height: 70vh;
    }
}

@media (max-width: 767.98px) {
    .drawer-content.expanded {
        max-height: 60vh;
    }
    
    .mobile-preview-drawer .certificate-preview-container {
        min-height: 300px;
        max-height: 55vh;
    }
    
    .preview-controls {
        padding: 0.5rem;
        background-color: #f8f9fa;
        border-top: 1px solid #e9ecef;
    }
}

@media (max-width: 575.98px) {
    .drawer-content.expanded {
        max-height: 50vh;
    }
    
    .mobile-preview-drawer .certificate-preview-container {
        min-height: 250px;
        max-height: 45vh;
    }
    
    .mobile-preview-drawer .certificate-preview-wrapper {
        max-height: 40vh;
    }
    
    .mobile-preview-drawer iframe {
        min-height: 200px;
        max-height: 40vh;
    }
    
    .drawer-handle {
        padding: 0.5rem;
    }
    
    .drawer-title {
        font-size: 0.8rem;
    }
}

/* Добавляем индикатор загрузки для iframe */
.certificate-preview-container::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 40px;
    height: 40px;
    border: 4px solid rgba(0, 0, 0, 0.1);
    border-top-color: #0d6efd;
    border-radius: 50%;
    animation: spin 1s infinite linear;
    z-index: 0;
}

.certificate-preview-loaded .certificate-preview-container::before {
    display: none;
}

@keyframes spin {
    to { transform: translate(-50%, -50%) rotate(360deg); }
}
</style>
<?php /**PATH C:\OSPanel\domains\sert\resources\views/entrepreneur/certificates/partials/certificate_styles.blade.php ENDPATH**/ ?>