<script>
document.addEventListener('DOMContentLoaded', () => {
  // Initialize Fabric.js canvas
  const canvas = new fabric.Canvas('canvas', {
    preserveObjectStacking: true,
    selection: true
  });

  // State variables
  let isDrawingMode = false;
  let isInCropMode = false;
  let isMaskMode = false;
  let isImageLoaded = false;
  let cropRect = null;
  let activeTextObj = null;
  let currentBrushColor = '#ffffff';
  let currentBrushSize = 10;
  let currentMaskShape = 'rectangle';

  // Filters definitions
  const filters = {
    normal: { name: 'Обычный', params: {} },
    vintage: { name: 'Винтаж', params: { brightness: -0.1, contrast: 0.1, sepia: true, vignette: true } },
    sepia: { name: 'Сепия', params: { sepia: true } },
    grayscale: { name: 'Ч/Б', params: { grayscale: true } },
    lomo: { name: 'Ломо', params: { brightness: 0.05, contrast: 0.2, saturation: 0.3 } },
    clarity: { name: 'Четкость', params: { contrast: 0.3, sharpen: true } },
    // add more as needed...
  };

  // Sticker definitions
  const stickers = [
    { icon: 'heart', color: '#ff6b6b', svg: '<i class="fas fa-heart fa-2x"></i>' },
    { icon: 'star', color: '#ffd43b', svg: '<i class="fas fa-star fa-2x"></i>' },
    // add more...
  ];

  //
  // -- Utility functions --
  //

  function setCanvasSize() {
    const container = document.querySelector('.canvas-container');
    canvas.setWidth(container.clientWidth);
    canvas.setHeight(container.clientHeight);
    canvas.renderAll();
  }

  function centerBackgroundImage() {
    const bg = canvas.backgroundImage;
    if (!bg) return;
    bg.set({
      left: canvas.getWidth() / 2,
      top: canvas.getHeight() / 2,
      originX: 'center',
      originY: 'center'
    });
    canvas.renderAll();
  }

  //
  // -- History (Undo/Redo) --
  //

  const history = {
    states: [],
    index: -1,
    max: 30,

    save() {
      // truncate future states
      if (this.index < this.states.length - 1) {
        this.states = this.states.slice(0, this.index + 1);
      }
      const json = canvas.toJSON(['selectable', 'hasControls', 'name']);
      this.states.push(JSON.stringify(json));
      if (this.states.length > this.max) this.states.shift();
      this.index = this.states.length - 1;
      updateUndoRedoButtons();
    },

    undo() {
      if (this.index > 0) {
        this.index--;
        this._load();
      }
    },

    redo() {
      if (this.index < this.states.length - 1) {
        this.index++;
        this._load();
      }
    },

    _load() {
      const state = this.states[this.index];
      canvas.loadFromJSON(state, () => {
        canvas.renderAll();
        updateUndoRedoButtons();
      });
    },

    init() {
      this.save();
    }
  };

  function updateUndoRedoButtons() {
    document.getElementById('undo-btn').classList.toggle('disabled', history.index <= 0);
    document.getElementById('redo-btn').classList.toggle('disabled', history.index >= history.states.length - 1);
  }

  //
  // -- Image loading --
  //

  document.getElementById('upload-image-btn').addEventListener('click', () => {
    document.getElementById('image-upload').click();
  });

  document.getElementById('image-upload').addEventListener('change', e => {
    const file = e.target.files[0];
    if (!file) return;
    if (file.size > 20 * 1024 * 1024) return alert('Максимум 20MB');
    const valid = ['image/jpeg','image/png','image/gif','image/webp'];
    if (!valid.includes(file.type)) return alert('Неподдерж. формат');

    const reader = new FileReader();
    reader.onload = ev => {
      fabric.Image.fromURL(ev.target.result, img => {
        canvas.clear();
        canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas), {
          scaleX: canvas.getWidth() / img.width,
          scaleY: canvas.getHeight() / img.height,
          originX: 'center',
          originY: 'center',
          left: canvas.getWidth()/2,
          top: canvas.getHeight()/2,
          name: 'mainImage'
        });
        isImageLoaded = true;
        history.init();
      });
    };
    reader.readAsDataURL(file);
  });

  //
  // -- Filters & Adjustments --
  //

  function applyFilter(filterKey) {
    if (!isImageLoaded) return;
    const bg = canvas.backgroundImage;
    const fdef = filters[filterKey];
    const farr = [];

    // clear existing
    bg.filters = [];

    // apply brightness
    if ('brightness' in fdef.params) {
      farr.push(new fabric.Image.filters.Brightness({ brightness: fdef.params.brightness }));
    }
    // contrast
    if ('contrast' in fdef.params) {
      farr.push(new fabric.Image.filters.Contrast({ contrast: fdef.params.contrast }));
    }
    // sepia
    if (fdef.params.sepia) {
      farr.push(new fabric.Image.filters.Sepia());
    }
    // grayscale
    if (fdef.params.grayscale) {
      farr.push(new fabric.Image.filters.Grayscale());
    }
    // saturation
    if ('saturation' in fdef.params) {
      farr.push(new fabric.Image.filters.Saturation({ saturation: fdef.params.saturation }));
    }
    // blur
    if ('blur' in fdef.params) {
      farr.push(new fabric.Image.filters.Blur({ blur: fdef.params.blur }));
    }
    // noise
    if ('noise' in fdef.params) {
      farr.push(new fabric.Image.filters.Noise({ noise: fdef.params.noise }));
    }
    // sharpen (approximate)
    if (fdef.params.sharpen) {
      farr.push(new fabric.Image.filters.Convolute({
        matrix: [  0, -1,  0, -1,  5, -1,  0, -1,  0 ]
      }));
    }

    bg.filters = farr;
    bg.applyFilters();
    canvas.renderAll();
    history.save();
  }

  function initFilters() {
    document.querySelectorAll('.filter-item').forEach(el => {
      el.addEventListener('click', () => {
        const key = el.getAttribute('data-filter');
        applyFilter(key);
        document.querySelectorAll('.filter-item').forEach(i => i.classList.remove('active'));
        el.classList.add('active');
      });
    });
  }

  // Adjustments sliders
  ['brightness','contrast','saturation'].forEach(adj => {
    const slider = document.getElementById(adj + '-slider');
    const valueTxt = document.getElementById(adj + '-value');
    slider.addEventListener('input', () => {
      const v = parseInt(slider.value,10) / 100;
      valueTxt.textContent = slider.value;
      if (!isImageLoaded) return;
      const bg = canvas.backgroundImage;
      // remove any previous of same type
      bg.filters = bg.filters.filter(f => !(f.constructor.name === {
        brightness: 'Brightness',
        contrast: 'Contrast',
        saturation: 'Saturation'
      }[adj]));
      // add new
      const cls = {
        brightness: fabric.Image.filters.Brightness,
        contrast: fabric.Image.filters.Contrast,
        saturation: fabric.Image.filters.Saturation
      }[adj];
      bg.filters.push(new cls({ [adj]: v }));
      bg.applyFilters();
      canvas.renderAll();
      history.save();
    });
  });

  //
  // -- Tools: Crop, Rotate, Text, Draw, Mask, Undo/Redo, Reset --
  //

  function startCropMode() {
    if (!isImageLoaded) return;
    isInCropMode = true;
    const w = canvas.getWidth() * 0.8;
    const h = canvas.getHeight() * 0.8;
    cropRect = new fabric.Rect({
      left: (canvas.getWidth()-w)/2, top: (canvas.getHeight()-h)/2,
      width: w, height: h,
      fill: 'rgba(0,0,0,0.3)', stroke: '#fff', strokeDashArray:[5,5],
      hasControls: true, selectable: true
    });
    canvas.add(cropRect).setActiveObject(cropRect);
  }

  function applyCrop() {
    if (!cropRect) return;
    history.save();
    const bg = canvas.backgroundImage;
    const rect = cropRect.getBoundingRect();
    bg.clipPath = new fabric.Rect({
      left: rect.left - bg.left + bg.width/2*bg.scaleX,
      top: rect.top - bg.top + bg.height/2*bg.scaleY,
      width: rect.width, height: rect.height,
      originX: 'center', originY: 'center'
    });
    canvas.remove(cropRect);
    isInCropMode = false;
    canvas.renderAll();
  }

  document.getElementById('crop-btn').addEventListener('click', () => {
    if (isInCropMode) {
      applyCrop();
      document.getElementById('crop-btn').classList.remove('active');
    } else {
      startCropMode();
      document.getElementById('crop-btn').classList.add('active');
    }
  });

  document.getElementById('rotate-btn').addEventListener('click', () => {
    if (!isImageLoaded) return;
    history.save();
    const bg = canvas.backgroundImage;
    const angle = (bg.angle||0) + 90;
    bg.rotate(angle);
    canvas.renderAll();
  });

  // Text tool
  function showTextEditor() {
    document.getElementById('text-editor').style.display = 'flex';
    document.getElementById('text-input').focus();
  }
  function hideTextEditor() {
    document.getElementById('text-editor').style.display = 'none';
    document.getElementById('text-input').value = '';
  }
  function applyText() {
    const txt = document.getElementById('text-input').value.trim();
    if (!txt || !isImageLoaded) { hideTextEditor(); return; }
    history.save();
    const colorOpt = document.querySelector('.color-option.active');
    const color = colorOpt.getAttribute('data-color');
    const bold = document.getElementById('text-bold-btn').classList.contains('active');
    const italic = document.getElementById('text-italic-btn').classList.contains('active');
    const font = document.getElementById('font-family').value;
    const textObj = new fabric.Text(txt, {
      left: canvas.getWidth()/2,
      top: canvas.getHeight()/2,
      originX:'center', originY:'center',
      fill: color, fontFamily: font,
      fontWeight: bold?'bold':'normal',
      fontStyle: italic?'italic':'normal'
    });
    canvas.add(textObj).setActiveObject(textObj).renderAll();
    hideTextEditor();
  }
  document.getElementById('text-btn').addEventListener('click', showTextEditor);
  document.getElementById('cancel-text-btn').addEventListener('click', hideTextEditor);
  document.getElementById('apply-text-btn').addEventListener('click', applyText);

  // Toggle bold/italic
  document.getElementById('text-bold-btn').addEventListener('click', e => e.currentTarget.classList.toggle('active'));
  document.getElementById('text-italic-btn').addEventListener('click', e => e.currentTarget.classList.toggle('active'));
  document.querySelectorAll('.color-option').forEach(opt => {
    opt.addEventListener('click', () => {
      document.querySelectorAll('.color-option').forEach(o => o.classList.remove('active'));
      opt.classList.add('active');
    });
  });

  // Drawing tool
  document.getElementById('draw-btn').addEventListener('click', e => {
    if (!isImageLoaded) return;
    isDrawingMode = !isDrawingMode;
    canvas.isDrawingMode = isDrawingMode;
    e.currentTarget.classList.toggle('active', isDrawingMode);
    if (isDrawingMode) {
      canvas.freeDrawingBrush.color = currentBrushColor;
      canvas.freeDrawingBrush.width = currentBrushSize;
      showDrawingPalette();
    } else {
      hideDrawingPalette();
    }
  });

  // Drawing palette
  function showDrawingPalette() {
    let pal = document.getElementById('drawing-palette');
    if (!pal) {
      pal = document.createElement('div');
      pal.id = 'drawing-palette';
      pal.innerHTML = `
        <div class="palette-title">Цвет кисти:</div>
        <div class="color-palette">
          ${['#ffffff','#000000','#ff0000','#00ff00','#0000ff','#ffff00'].map(c=>
            `<div class="color-swatch" data-color="${c}" style="background:${c}"></div>`
          ).join('')}
        </div>
        <div>Размер: <span id="brush-size-value">${currentBrushSize}</span>px</div>
        <input type="range" id="brush-size" min="1" max="50" value="${currentBrushSize}">
        <button id="close-palette">Закрыть</button>
      `;
      document.body.appendChild(pal);
      pal.querySelectorAll('.color-swatch').forEach(s => {
        s.addEventListener('click', () => {
          currentBrushColor = s.getAttribute('data-color');
          canvas.freeDrawingBrush.color = currentBrushColor;
          pal.querySelectorAll('.color-swatch').forEach(o=>o.classList.remove('active'));
          s.classList.add('active');
        });
      });
      pal.querySelector('#brush-size').addEventListener('input', ev => {
        currentBrushSize = +ev.target.value;
        document.getElementById('brush-size-value').textContent = currentBrushSize;
        canvas.freeDrawingBrush.width = currentBrushSize;
      });
      pal.querySelector('#close-palette').addEventListener('click', hideDrawingPalette);
    }
    pal.style.display = 'flex';
  }

  function hideDrawingPalette() {
    const pal = document.getElementById('drawing-palette');
    if (pal) pal.style.display = 'none';
  }

  // Mask tool
  document.getElementById('mask-btn').addEventListener('click', e => {
    if (!isImageLoaded) return;
    isMaskMode = !isMaskMode;
    e.currentTarget.classList.toggle('active', isMaskMode);
    if (isMaskMode) {
      canvas.backgroundImage.selectable = false;
      canvas.on('mouse:down', createMaskShape);
      showMaskControls();
    } else {
      exitMaskMode();
    }
  });

  function showMaskControls() {
    let ctrl = document.getElementById('mask-controls');
    if (!ctrl) {
      ctrl = document.createElement('div');
      ctrl.id = 'mask-controls';
      ctrl.innerHTML = `
        <div>
          ${['rectangle','circle','triangle','heart'].map(shape=>
            `<button class="mask-shape" data-shape="${shape}">${shape}</button>`
          ).join('')}
        </div>
        <button id="exit-mask">Выйти</button>
      `;
      document.body.appendChild(ctrl);
      ctrl.querySelectorAll('.mask-shape').forEach(b => {
        b.addEventListener('click', () => {
          currentMaskShape = b.getAttribute('data-shape');
          ctrl.querySelectorAll('.mask-shape').forEach(o=>o.classList.remove('active'));
          b.classList.add('active');
        });
      });
      ctrl.querySelector('#exit-mask').addEventListener('click', exitMaskMode);
    }
    ctrl.style.display = 'flex';
    ctrl.querySelector(`.mask-shape[data-shape="${currentMaskShape}"]`)?.classList.add('active');
  }

  function hideMaskControls() {
    const ctrl = document.getElementById('mask-controls');
    if (ctrl) ctrl.style.display = 'none';
  }

  function createMaskShape(ev) {
    if (!isMaskMode) return;
    const p = canvas.getPointer(ev.e);
    let obj;
    switch (currentMaskShape) {
      case 'rectangle':
        obj = new fabric.Rect({ left: p.x, top: p.y, width:100, height:100, fill:'rgba(0,0,0,0.5)' });
        break;
      case 'circle':
        obj = new fabric.Circle({ left: p.x, top: p.y, radius:50, fill:'rgba(0,0,0,0.5)' });
        break;
      case 'triangle':
        obj = new fabric.Triangle({ left: p.x, top: p.y, width:100, height:100, fill:'rgba(0,0,0,0.5)' });
        break;
      case 'heart':
        const path = 'M10,30 A20,20 0,0,1 50,30 A20,20 0,0,1 90,30 Q90,60 50,90 Q10,60 10,30 z';
        obj = fabric.Path.fromSVG(path);
        obj.set({ left:p.x, top:p.y, scaleX:0.3, scaleY:0.3, fill:'rgba(0,0,0,0.5)' });
        break;
    }
    if (obj) {
      obj.set({ originX:'center', originY:'center' });
      canvas.add(obj).setActiveObject(obj).renderAll();
      applyMask(obj);
    }
  }

  function applyMask(maskObj) {
    history.save();
    const bg = canvas.backgroundImage;
    const m = fabric.util.object.clone(maskObj);
    m.set({ left: maskObj.left - bg.left, top: maskObj.top - bg.top, originX:'center', originY:'center' });
    bg.clipPath = m;
    canvas.remove(maskObj).renderAll();
    exitMaskMode();
  }

  function exitMaskMode() {
    isMaskMode = false;
    canvas.off('mouse:down', createMaskShape);
    canvas.backgroundImage.selectable = false;
    hideMaskControls();
    document.getElementById('mask-btn').classList.remove('active');
  }

  // Undo / Redo / Reset
  document.getElementById('undo-btn').addEventListener('click', () => {
    history.undo();
  });
  document.getElementById('redo-btn').addEventListener('click', () => {
    history.redo();
  });
  document.getElementById('reset-btn').addEventListener('click', () => {
    if (confirm('Сбросить все изменения?')) {
      history.init();
      canvas.clear();
    }
  });

  //
  // -- Stickers --
  //

  function initStickers() {
    document.querySelectorAll('.sticker-item').forEach(el => {
      el.addEventListener('click', () => {
        const icon = el.getAttribute('data-icon');
        const st = stickers.find(s => s.icon === icon);
        if (!st || !isImageLoaded) return;
        history.save();
        const div = document.createElement('div');
        div.innerHTML = st.svg;
        const node = div.firstChild;
        fabric.loadSVGFromString(node.outerHTML, (objs, opts) => {
          const grp = fabric.util.groupSVGElements(objs, opts);
          grp.set({
            left: canvas.getWidth()/2,
            top: canvas.getHeight()/2,
            originX:'center', originY:'center',
            scaleX:1.5, scaleY:1.5, fill: st.color
          });
          canvas.add(grp).setActiveObject(grp).renderAll();
        });
      });
    });
    document.getElementById('add-sticker-btn').addEventListener('click', () => {
      document.getElementById('sticker-upload').click();
    });
    document.getElementById('sticker-upload').addEventListener('change', e => {
      const f = e.target.files[0];
      if (!f) return;
      const reader = new FileReader();
      reader.onload = ev => {
        fabric.Image.fromURL(ev.target.result, img => {
          history.save();
          img.set({ left:canvas.getWidth()/2, top:canvas.getHeight()/2, originX:'center', originY:'center', scaleX:1, scaleY:1, name:'sticker' });
          canvas.add(img).setActiveObject(img).renderAll();
        });
      };
      reader.readAsDataURL(f);
    });
  }

  //
  // -- Camera capture --
  //

  function showCameraModal() {
    const modal = document.createElement('div');
    modal.className = 'editor-modal';
    modal.innerHTML = `
      <div class="video-container">
        <video id="camera-feed" autoplay playsinline></video>
        <div id="camera-loading"><i class="fas fa-spinner fa-spin"></i> Подключение...</div>
      </div>
      <div class="controls">
        <button id="cancel-camera">Отмена</button>
        <button id="capture-camera">Сфотографировать</button>
      </div>
    `;
    document.body.appendChild(modal);

    const video = modal.querySelector('#camera-feed');
    const loading = modal.querySelector('#camera-loading');
    const cancelBtn = modal.querySelector('#cancel-camera');
    const snapBtn = modal.querySelector('#capture-camera');

    navigator.mediaDevices.getUserMedia({ video: true, audio: false })
      .then(stream => {
        video.srcObject = stream;
        loading.style.display = 'none';
        snapBtn.addEventListener('click', () => {
          const c = document.createElement('canvas');
          c.width = video.videoWidth;
          c.height = video.videoHeight;
          c.getContext('2d').drawImage(video,0,0);
          c.toBlob(blob => {
            document.getElementById('image-upload').files = new DataTransfer().files;
            const file = new File([blob],'capture.jpg',{ type:'image/jpeg' });
            const dt = new DataTransfer();
            dt.items.add(file);
            document.getElementById('image-upload').files = dt.files;
            document.getElementById('image-upload').dispatchEvent(new Event('change'));
            stream.getTracks().forEach(t=>t.stop());
            modal.remove();
          }, 'image/jpeg', 0.95);
        });
      })
      .catch(err => {
        loading.textContent = 'Ошибка доступа к камере';
      });

    cancelBtn.addEventListener('click', () => {
      modal.remove();
    });
  }

  document.getElementById('capture-image-btn').addEventListener('click', showCameraModal);

  //
  // -- Initialization --
  //

  function initUI() {
    // Tabs
    document.querySelectorAll('.tab-button').forEach(tab => {
      tab.addEventListener('click', () => {
        document.querySelectorAll('.tab-button').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.toolbar-content').forEach(c => c.style.display = 'none');
        tab.classList.add('active');
        document.getElementById(`${tab.dataset.tab}-tab`).style.display = 'block';
        // exit special modes
        if (isDrawingMode) { document.getElementById('draw-btn').click(); }
        if (isInCropMode) { document.getElementById('crop-btn').click(); }
        if (isMaskMode) { document.getElementById('mask-btn').click(); }
      });
    });
  }

  // Run inits
  setCanvasSize();
  window.addEventListener('resize', setCanvasSize);
  initFilters();
  initUI();
  initStickers();
  history.init();
});

</script>

<?php /**PATH C:\OSPanel\domains\sert\resources\views/photo-editor/partials/_scripts.blade.php ENDPATH**/ ?>