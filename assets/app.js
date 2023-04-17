/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';


const fileCotnainer = document.getElementById("file-input-container");
const fileInput = document.getElementById("accounting_document_scan");
const filePreviewWraper = document.getElementById("file-preview-wraper");
const filePreview = document.getElementById("file-preview");

["drag", "dragstart", "dragend", "dragover", "dragenter", "dragleave", "drop"].forEach(evt => {
    fileCotnainer.addEventListener(evt, e => {e.preventDefault(); e.stopPropagation()}, false)
});

["dragover", "dragenter"].forEach(evt => {
    fileCotnainer.addEventListener(evt, e => {
        fileCotnainer.classList.add('bg-primary');
        fileCotnainer.classList.add('text-white');
        fileCotnainer.classList.remove('text-primary');
    })
});

["dragleave", "dragend", "drop"].forEach(evt => {
    fileCotnainer.addEventListener(evt, e => {
        fileCotnainer.classList.remove('bg-primary');
        fileCotnainer.classList.remove('text-white');
        fileCotnainer.classList.add('text-primary');
    });
});

fileCotnainer.addEventListener("drop", (e) => {
    const files = e.dataTransfer.files;
    const mime = files[0].type;

    if (mime !== 'application/pdf') {
        return false;
    }

    fileInput.files = files;
    fileInput.dispatchEvent(new Event('change'));
});
fileCotnainer.addEventListener("click", (e) => {
    fileInput.click();
});

fileInput.addEventListener("change", (e) => {
    const file = e.target.files[0];
    const url = URL.createObjectURL(file);

    fileCotnainer.classList.add('d-none');

    filePreview.src = url;
    filePreviewWraper.classList.remove('d-none');
});
