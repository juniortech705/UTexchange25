// =============================
// GLOBAL STATE (create + edit)
// =============================
let filesList = [];
let selectedCover = 0;
let dragIndex = null;

// =============================
// CREATE / ADD FILES
// =============================
function handleFiles(files) {
    const newFiles = Array.from(files);
    filesList = [...filesList, ...newFiles].slice(0, 8);
    renderPreview();
}

// =============================
// RENDER PREVIEW (CREATE + EDIT NEW FILES)
// =============================
function renderPreview() {
    const preview = document.getElementById('photo-preview');
    if (!preview) return;

    preview.innerHTML = '';

    const dt = new DataTransfer();

    filesList.forEach((file, index) => {
        dt.items.add(file);

        const reader = new FileReader();

        reader.onload = e => {
            const div = document.createElement('div');

            div.draggable = true;

            div.style.cssText = `
                position:relative;
                width:90px;height:90px;
                border-radius:10px;
                overflow:hidden;
                border:2px solid ${index === selectedCover ? '#0056b3' : '#e5e7eb'};
                cursor:grab;
            `;

            div.innerHTML = `
                <img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;">

                <button type="button"
                    onclick="removeImage(${index})"
                    style="
                        position:absolute;
                        top:4px;
                        right:4px;
                        background:rgba(239,68,68,.9);
                        color:white;
                        border:none;
                        border-radius:50%;
                        width:18px;
                        height:18px;
                        font-size:10px;
                        cursor:pointer;
                    ">×</button>
            `;

            // drag start
            div.addEventListener('dragstart', () => {
                dragIndex = index;
            });

            // drag over
            div.addEventListener('dragover', (e) => {
                e.preventDefault();
            });

            // drop reorder
            div.addEventListener('drop', () => {
                if (dragIndex === null) return;

                const dragged = filesList[dragIndex];
                filesList.splice(dragIndex, 1);
                filesList.splice(index, 0, dragged);


                dragIndex = null;
                renderPreview();
            });

            preview.appendChild(div);
        };

        reader.readAsDataURL(file);
    });

    const input = document.getElementById('file-input');
    if (input) input.files = dt.files;
}

// =============================
// REMOVE IMAGE (CREATE MODE)
// =============================
function removeImage(index) {
    filesList.splice(index, 1);

    if (selectedCover === index) {
        selectedCover = 0;
    } else if (index < selectedCover) {
        selectedCover--;
    }

    renderPreview();
}