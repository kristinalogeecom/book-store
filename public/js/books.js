import { get, post } from './ajax.js';

document.addEventListener('DOMContentLoaded', () => {
    const app = document.getElementById('bookApp');
    const authorId = new URLSearchParams(window.location.search).get('author_id');
    let books = [];

    const apiBase = '/index.php?api=books&action=';

    const createOverlay = () => {
        const overlay = document.createElement('div');
        overlay.id = 'overlay';
        overlay.className = 'overlay';
        return overlay;
    };

    const createForm = (book = null, type = 'create') => {
        const overlay = createOverlay();
        const container = document.createElement('div');
        container.className = 'form-container';

        const title = document.createElement('h2');
        title.textContent = type === 'edit' ? 'Edit Book' : type === 'delete' ? 'Delete Book' : 'Book Create';

        const hr = document.createElement('hr');
        hr.className = 'form-divider';

        const titleLabel = document.createElement('label');
        titleLabel.textContent = 'Title';
        const titleInput = document.createElement('input');
        titleInput.type = 'text';
        titleInput.name = 'title';
        titleInput.value = book?.title || '';

        const yearLabel = document.createElement('label');
        yearLabel.textContent = 'Year';
        const yearInput = document.createElement('input');
        yearInput.type = 'text';
        yearInput.name = 'year';
        yearInput.value = book?.year || '';

        const error = document.createElement('div');
        error.className = 'error-msg';

        const btnWrapper = document.createElement('div');
        btnWrapper.className = 'btn-wrapper';

        const submitBtn = document.createElement('button');
        submitBtn.textContent = type === 'edit' ? 'Save' : type === 'delete' ? 'Delete' : 'Create';
        submitBtn.type = 'submit';
        if (type === 'delete') {
            submitBtn.classList.add('delete-submit');
        }


        const backBtn = document.createElement('button');
        backBtn.textContent = 'Back';
        backBtn.type = 'button';
        backBtn.className = 'back-btn';

        backBtn.addEventListener('click', () => overlay.remove());

        btnWrapper.append(submitBtn, backBtn);
        container.append(title, hr);

        if (type !== 'delete') {
            container.append(titleLabel, titleInput, yearLabel, yearInput);
        } else {
            const confirm = document.createElement('p');
            confirm.textContent = 'Are you sure you want to delete this book?';
            container.append(confirm);
        }

        container.append(error, btnWrapper);
        overlay.appendChild(container);

        submitBtn.addEventListener('click', e => {
            e.preventDefault();
            const title = titleInput.value.trim();
            const year = parseInt(yearInput.value);

            if (type !== 'delete' && (!title || isNaN(year))) {
                error.textContent = 'Please fill in all fields correctly.';
                return;
            }

            if (type === 'create') {
                post(`${apiBase}create`, { author_id: authorId, title, year })
                    .then(resp => {
                        if (resp.success) {
                            overlay.remove();
                            loadBooks();
                        }
                    });
            } else if (type === 'edit') {
                post(`${apiBase}edit`, { id: book.id, title, year })
                    .then(resp => {
                        if (resp.success) {
                            overlay.remove();
                            loadBooks();
                        }
                    });
            } else if (type === 'delete') {
                post(`${apiBase}delete`, { id: book.id })
                    .then(resp => {
                        if (resp.success) {
                            overlay.remove();
                            loadBooks();
                        }
                    });
            }
        });

        return overlay;
    };

    const render = () => {
        app.innerHTML = '';

        const backBtn = document.createElement('a');
        backBtn.className = 'back';
        backBtn.href = 'index.php?page=authorsList';

        const backIcon = document.createElement('i');
        backIcon.className = 'fa-solid fa-arrow-left';
        backBtn.appendChild(backIcon);

        app.appendChild(backBtn);

        const wrapper = document.createElement('div');
        wrapper.className = 'table-wrapper';

        const table = document.createElement('table');
        table.className = 'book-table';

        const thead = document.createElement('thead');
        const headerRow = document.createElement('tr');

        const bookHeader = document.createElement('th');
        bookHeader.textContent = 'Book';

        const actionsHeader = document.createElement('th');
        actionsHeader.textContent = 'Actions';

        headerRow.append(bookHeader, actionsHeader);
        thead.appendChild(headerRow);
        table.appendChild(thead);

        const tbody = document.createElement('tbody');

        books.forEach(book => {

            const row = document.createElement('tr');

            const titleCell = document.createElement('td');
            const strong = document.createElement('strong');
            strong.textContent = book.title;

            const yearText = document.createTextNode(` (${book.year})`);

            titleCell.appendChild(strong);
            titleCell.appendChild(yearText);

            const actionsCell = document.createElement('td');

            const editBtn = document.createElement('button');
            editBtn.className = 'edit';
            editBtn.dataset.id = book.id;

            const editIcon = document.createElement('i');
            editIcon.className = 'fa-solid fa-pen-to-square edit-icon';
            editBtn.appendChild(editIcon);

            const deleteBtn = document.createElement('button');
            deleteBtn.className = 'delete';
            deleteBtn.dataset.id = book.id;

            const deleteIcon = document.createElement('i');
            deleteIcon.className = 'fa-solid fa-trash delete-icon';
            deleteBtn.appendChild(deleteIcon);

            actionsCell.append(editBtn, deleteBtn);
            row.append(titleCell, actionsCell);
            tbody.appendChild(row);
        });

        table.appendChild(tbody);
        wrapper.appendChild(table);
        app.appendChild(wrapper);

        document.querySelectorAll('.edit').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = parseInt(btn.dataset.id);
                const book = books.find(b => b.id === id);
                const form = createForm(book, 'edit');
                document.body.appendChild(form);
            });
        });

        document.querySelectorAll('.delete').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = parseInt(btn.dataset.id);
                const book = books.find(b => b.id === id);
                const form = createForm(book, 'delete');
                document.body.appendChild(form);
            });
        });

        const addBtn = document.createElement('a');
        addBtn.className = 'add';
        addBtn.href = '#';

        const plusIcon = document.createElement('i');
        plusIcon.className = 'fa-solid fa-plus';
        addBtn.appendChild(plusIcon);

        addBtn.addEventListener('click', (e) => {
            e.preventDefault();
            const form = createForm(null, 'create');
            document.body.appendChild(form);
        });

        app.appendChild(addBtn);
    };


    const loadBooks = () => {

        get(`${apiBase}getByAuthor&author_id=${authorId}`)
            .then(data => {
                books = data.books || [];
                render();
            });
    };

    loadBooks();

});
