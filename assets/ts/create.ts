(() => {
    const createIcon = document.querySelector<HTMLImageElement>("img.icon.create")!;
    createIcon.src = "assets/images/icon/create_black.svg";

    // ===== ELEMENTS =====
    const webcam = document.getElementById('webcam') as HTMLVideoElement;
    const uploadedPreview = document.getElementById('uploaded-preview') as HTMLImageElement;
    const stickerCanvas = document.getElementById('sticker-canvas') as HTMLCanvasElement;
    const ctx = stickerCanvas.getContext('2d')!;
    const placeholder = document.getElementById('preview-placeholder')!;
    const btnWebcam = document.getElementById('btn-webcam') as HTMLButtonElement;
    const btnUpload = document.getElementById('btn-upload') as HTMLLabelElement;
    const fileInput = document.getElementById('file-input') as HTMLInputElement;
    const stickersGrid = document.getElementById('stickers-grid')!;
    const captionInput = document.getElementById('caption-input') as HTMLTextAreaElement;
    const captionLength = document.getElementById('caption-length')!;
    const btnCapture = document.getElementById('btn-capture') as HTMLButtonElement;
    const btnShare = document.querySelector<HTMLButtonElement>('.btn-share');
    const editorPreview = document.querySelector<HTMLElement>('.editor-preview')!;
    const stickerControls = document.getElementById('sticker-controls')!;

    interface PlacedSticker {
        img: HTMLImageElement;
        x: number;
        y: number;
        width: number;
        height: number;
        name: string;
    }

    interface CaptureResponse {
        success: boolean;
        error?: string;
    }

    let currentMode = 'webcam';
    let webcamStream: MediaStream | null = null;
    let uploadedFile: File | null = null;

    // ===== STICKER OBJECTS ON CANVAS =====
    let placedStickers: PlacedSticker[] = [];
    let dragging: PlacedSticker | null = null;
    let dragOffsetX = 0;
    let dragOffsetY = 0;
    let activeSticker: PlacedSticker | null = null;

    // ===== WEBCAM =====
    async function startWebcam() {
        try {
            webcamStream = await navigator.mediaDevices.getUserMedia({
                video: { width: { ideal: 1280 }, height: { ideal: 960 } },
                audio: false
            });
            webcam.srcObject = webcamStream;
            webcam.style.display = 'block';
            uploadedPreview.style.display = 'none';
            placeholder.style.display = 'none';
            resizeCanvas();
        } catch (err) {
            placeholder.style.display = 'flex';
        }
    }

    function stopWebcam() {
        if (webcamStream) {
            webcamStream.getTracks().forEach(track => track.stop());
            webcamStream = null;
        }
        webcam.srcObject = null;
        webcam.style.display = 'none';
    }

    // ===== CANVAS RESIZE =====
    function resizeCanvas() {
        stickerCanvas.width = editorPreview.clientWidth;
        stickerCanvas.height = editorPreview.clientHeight;
        drawStickers();
    }

    window.addEventListener('resize', resizeCanvas);

    // ===== DRAW STICKERS =====
    function drawStickers() {
        ctx.clearRect(0, 0, stickerCanvas.width, stickerCanvas.height);
        placedStickers.forEach(s => {
            ctx.drawImage(s.img, s.x, s.y, s.width, s.height);


        });

        // Show/hide HTML controls
        stickerControls.style.display = activeSticker ? 'flex' : 'none';
    }

    // ===== RESIZE STICKER =====
    function resizeSticker(sticker: PlacedSticker, scale: number) {
        const newW = sticker.width * scale;
        const newH = sticker.height * scale;
        if (newW < 30 || newW > stickerCanvas.width * 0.8) return;
        sticker.x -= (newW - sticker.width) / 2;
        sticker.y -= (newH - sticker.height) / 2;
        sticker.width = newW;
        sticker.height = newH;
        drawStickers();
    }

    document.getElementById('sticker-plus')!.addEventListener('click', () => {
        if (activeSticker) resizeSticker(activeSticker, 1.15);
    });

    document.getElementById('sticker-minus')!.addEventListener('click', () => {
        if (activeSticker) resizeSticker(activeSticker, 0.85);
    });

    // ===== SOURCE TOGGLE =====
    btnWebcam.addEventListener('click', () => {
        currentMode = 'webcam';
        btnWebcam.classList.add('active');
        btnUpload.classList.remove('active');
        uploadedPreview.style.display = 'none';
        uploadedFile = null;
        fileInput.value = '';
        startWebcam();
    });

    fileInput.addEventListener('change', (e) => {
        const file = (e.target as HTMLInputElement).files![0];
        if (!file) return;

        if (!file.type.startsWith('image/')) {
            alert('Please select an image file');
            return;
        }

        uploadedFile = file;
        currentMode = 'upload';
        btnUpload.classList.add('active');
        btnWebcam.classList.remove('active');
        stopWebcam();

        const reader = new FileReader();
        reader.onload = (event) => {
            uploadedPreview.src = event.target!.result as string;
            uploadedPreview.style.display = 'block';
            placeholder.style.display = 'none';
            setTimeout(resizeCanvas, 100);
        };
        reader.readAsDataURL(file);
    });

    // ===== STICKERS LOADING =====
    function loadStickers() {
        fetch('index.php?action=get_stickers')
            .then(res => res.json())
            .then((stickers: string[]) => {
                stickersGrid.innerHTML = '';
                stickers.forEach(stickerName => {
                    const item = document.createElement('div');
                    item.className = 'sticker-item';
                    item.dataset.sticker = stickerName;
                    // Thumbnail in the grid; the full-size sticker is only loaded when selected
                    item.innerHTML = `<img src="assets/images/stickers/thumbs/${stickerName}" alt="${stickerName}" loading="lazy">`;

                    item.addEventListener('click', () => {
                        const isSelected = item.classList.contains('selected');

                        if (isSelected) {
                            item.classList.remove('selected');
                            placedStickers = placedStickers.filter(s => s.name !== stickerName);
                            if (activeSticker && activeSticker.name === stickerName) activeSticker = null;
                            drawStickers();
                        } else {
                            item.classList.add('selected');
                            addStickerToCanvas(stickerName);
                        }
                    });

                    stickersGrid.appendChild(item);
                });
            })
            .catch(err => console.error('Error loading stickers:', err));
    }

    function addStickerToCanvas(stickerName: string) {
        const img = new Image();
        img.src = 'assets/images/stickers/' + stickerName;
        img.onload = () => {
            const canvasW = stickerCanvas.width;
            const canvasH = stickerCanvas.height;

            const stickerW = canvasW * 0.25;
            const stickerH = (img.height / img.width) * stickerW;

            const x = (canvasW - stickerW) / 2;
            const y = (canvasH - stickerH) / 2;

            const newSticker: PlacedSticker = {
                img: img,
                x: x,
                y: y,
                width: stickerW,
                height: stickerH,
                name: stickerName
            };

            placedStickers.push(newSticker);
            activeSticker = newSticker;
            drawStickers();
        };
    }

    // ===== DRAG & DROP STICKERS =====
    stickerCanvas.style.pointerEvents = 'auto';

    function getCanvasPos(e: MouseEvent | TouchEvent) {
        const rect = stickerCanvas.getBoundingClientRect();
        const clientX = (e as TouchEvent).touches ? (e as TouchEvent).touches[0].clientX : (e as MouseEvent).clientX;
        const clientY = (e as TouchEvent).touches ? (e as TouchEvent).touches[0].clientY : (e as MouseEvent).clientY;
        return {
            x: clientX - rect.left,
            y: clientY - rect.top
        };
    }

    function findStickerAt(x: number, y: number): PlacedSticker | null {
        for (let i = placedStickers.length - 1; i >= 0; i--) {
            const s = placedStickers[i];
            if (x >= s.x && x <= s.x + s.width && y >= s.y && y <= s.y + s.height) {
                return s;
            }
        }
        return null;
    }

    // Mouse events
    stickerCanvas.addEventListener('mousedown', (e) => {
        const pos = getCanvasPos(e);
        const sticker = findStickerAt(pos.x, pos.y);
        if (sticker) {
            activeSticker = sticker;
            dragging = sticker;
            dragOffsetX = pos.x - sticker.x;
            dragOffsetY = pos.y - sticker.y;
            stickerCanvas.style.cursor = 'grabbing';
        } else {
            activeSticker = null;
        }
        drawStickers();
    });

    stickerCanvas.addEventListener('mousemove', (e) => {
        if (dragging) {
            const pos = getCanvasPos(e);
            dragging.x = pos.x - dragOffsetX;
            dragging.y = pos.y - dragOffsetY;
            drawStickers();
        } else {
            const pos = getCanvasPos(e);
            const sticker = findStickerAt(pos.x, pos.y);
            stickerCanvas.style.cursor = sticker ? 'grab' : 'default';
        }
    });

    stickerCanvas.addEventListener('mouseup', () => {
        dragging = null;
        stickerCanvas.style.cursor = 'default';
    });

    stickerCanvas.addEventListener('mouseleave', () => {
        dragging = null;
        stickerCanvas.style.cursor = 'default';
    });

    // Touch events (mobile)
    stickerCanvas.addEventListener('touchstart', (e) => {
        const pos = getCanvasPos(e);
        const sticker = findStickerAt(pos.x, pos.y);
        if (sticker) {
            activeSticker = sticker;
            dragging = sticker;
            dragOffsetX = pos.x - sticker.x;
            dragOffsetY = pos.y - sticker.y;
            e.preventDefault();
        } else {
            activeSticker = null;
        }
        drawStickers();
    });

    stickerCanvas.addEventListener('touchmove', (e) => {
        if (dragging) {
            const pos = getCanvasPos(e);
            dragging.x = pos.x - dragOffsetX;
            dragging.y = pos.y - dragOffsetY;
            drawStickers();
            e.preventDefault();
        }
    });

    stickerCanvas.addEventListener('touchend', () => {
        dragging = null;
    });

    // ===== CAPTION =====
    captionInput.addEventListener('input', () => {
        captionLength.textContent = String(captionInput.value.length);
    });

    // ===== CAPTURE =====
    btnCapture.addEventListener('click', async () => {
        if (currentMode === 'webcam' && !webcamStream) {
            alert('Please start the webcam first');
            return;
        }
        if (currentMode === 'upload' && !uploadedFile) {
            alert('Please upload an image first');
            return;
        }

        const canvas = document.createElement('canvas');
        const captureCtx = canvas.getContext('2d')!;

        let imgW: number, imgH: number;

        if (currentMode === 'webcam') {
            canvas.width = webcam.videoWidth;
            canvas.height = webcam.videoHeight;
            captureCtx.translate(canvas.width, 0);
            captureCtx.scale(-1, 1);
            captureCtx.drawImage(webcam, 0, 0);
            imgW = webcam.videoWidth;
            imgH = webcam.videoHeight;
        } else {
            canvas.width = uploadedPreview.naturalWidth;
            canvas.height = uploadedPreview.naturalHeight;
            captureCtx.drawImage(uploadedPreview, 0, 0);
            imgW = uploadedPreview.naturalWidth;
            imgH = uploadedPreview.naturalHeight;
        }

        // Calculate object-fit: contain offset
        const previewW = stickerCanvas.width;
        const previewH = stickerCanvas.height;
        const imgRatio = imgW / imgH;
        const previewRatio = previewW / previewH;

        let renderW: number, renderH: number, offsetX: number, offsetY: number;

        if (imgRatio > previewRatio) {
            // Image wider than preview: full width, letterbox top/bottom
            renderW = previewW;
            renderH = previewW / imgRatio;
            offsetX = 0;
            offsetY = (previewH - renderH) / 2;
        } else {
            // Image taller than preview: full height, pillarbox left/right
            renderH = previewH;
            renderW = previewH * imgRatio;
            offsetX = (previewW - renderW) / 2;
            offsetY = 0;
        }

        const scaleX = imgW / renderW;
        const scaleY = imgH / renderH;

        const stickersData = placedStickers.map(s => {
            const ratio = s.img.height / s.img.width;
            const scaledW = Math.round(s.width * scaleX);
            const scaledH = Math.round(scaledW * ratio);
            return {
                name: s.name,
                x: Math.round((s.x - offsetX) * scaleX),
                y: Math.round((s.y - offsetY) * scaleY),
                width: scaledW,
                height: scaledH
            };
        });

        canvas.toBlob(async (blob) => {
            const formData = new FormData();
            formData.append('image', blob!, 'capture.png');
            formData.append('caption', captionInput.value);
            formData.append('stickers', JSON.stringify(stickersData));

            try {
                btnCapture.style.pointerEvents = 'none';
                btnCapture.style.opacity = '0.5';

                const response = await fetch('index.php?action=capture', {
                    method: 'POST',
                    body: formData
                });

                const result: CaptureResponse = await response.json();

                if (result.success) {
                    // Freeze preview
                    const snapshot = document.getElementById('freeze-snapshot') as HTMLImageElement;
                    snapshot.src = canvas.toDataURL('image/png');
                    snapshot.style.display = 'block';
                    document.getElementById('webcam')!.style.display = 'none';
                    document.getElementById('uploaded-preview')!.style.display = 'none';
                    document.getElementById('sticker-canvas')!.style.display = 'none';

                    // Show popup
                    (document.getElementById('shared-modal') as HTMLDialogElement).showModal();
                    setTimeout(() => window.location.reload(), 2000);
                } else {
                    alert(result.error || 'Error capturing image');
                }
            } catch (err) {
                console.error('Capture error:', err);
                alert('Error capturing image');
            } finally {
                btnCapture.style.pointerEvents = '';
                btnCapture.style.opacity = '';
            }
        }, 'image/png');
    });

    // ===== PREVIOUS PHOTOS =====
    document.querySelectorAll<HTMLElement>('.previous-item').forEach(item => {
        item.addEventListener('click', () => {
            const postId = item.dataset.postId;
            window.location.href = 'index.php?action=post&id=' + postId;
        });
    });

    document.getElementById('sticker-delete')!.addEventListener('click', () => {
        if (activeSticker) {
            // Deselect in grid
            const gridItem = document.querySelector(`.sticker-item[data-sticker="${activeSticker.name}"]`);
            if (gridItem) gridItem.classList.remove('selected');
            // Remove from canvas
            placedStickers = placedStickers.filter(s => s !== activeSticker);
            activeSticker = null;
            drawStickers();
        }
    });


    // ===== INIT =====
    loadStickers();
    startWebcam();
})();
