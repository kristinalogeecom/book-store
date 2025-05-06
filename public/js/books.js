import { get, post } from './ajax.js';

document.addEventListener('DOMContentLoaded', () => {
    const app = document.getElementById('bookApp');
    const authorId = new URLSearchParams(window.location.search).get('author_id');
    let books = [];

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
                post('/api/books/createBook.php', { author_id: authorId, title, year })
                    .then(resp => {
                        if (resp.success) {
                            overlay.remove();
                            loadBooks();
                        }
                    });
            } else if (type === 'edit') {
                post('/api/books/editBook.php', { id: book.id, title, year })
                    .then(resp => {
                        if (resp.success) {
                            overlay.remove();
                            loadBooks();
                        }
                    });
            } else if (type === 'delete') {
                post('/api/books/deleteBook.php', { id: book.id })
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

        const wrapper = document.createElement('div');
        wrapper.className = 'table-wrapper';

        const table = document.createElement('table');
        table.className = 'book-table';

        const thead = document.createElement('thead');
        thead.innerHTML = '<tr><th>Book</th><th>Actions</th></tr>';
        table.appendChild(thead);

        const tbody = document.createElement('tbody');

        books.forEach(book => {
            const row = document.createElement('tr');

            const titleCell = document.createElement('td');
            titleCell.innerHTML = `<strong>${book.title}</strong> (${book.year})`;

            const actionsCell = document.createElement('td');
            actionsCell.innerHTML = `
                <button class="edit" data-id="${book.id}"><i class="fa-solid fa-pen-to-square edit-icon"></i></button>
                <button class="delete" data-id="${book.id}"><i class="fa-solid fa-minus-circle delete-icon"></i></button>
            `;

            row.append(titleCell, actionsCell);
            tbody.appendChild(row);
        });

        table.appendChild(tbody);
        app.appendChild(table);

        document.querySelectorAll('.edit').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                const book = books.find(b => b.id === id);
                const form = createForm(book, 'edit');
                document.body.appendChild(form);
            });
        });

        document.querySelectorAll('.delete').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                const book = books.find(b => b.id === id);
                const form = createForm(book, 'delete');
                document.body.appendChild(form);
            });
        });

        const addBtn = document.createElement('a');
        addBtn.className = 'add';
        addBtn.href = '#';
        addBtn.innerHTML = '<i class="fa-solid fa-plus"></i>';

        addBtn.addEventListener('click', (e) => {
            e.preventDefault();
            const form = createForm(null, 'create');
            document.body.appendChild(form);
        });

        app.appendChild(addBtn);
    };

    const loadBooks = () => {
        get(`/api/books/byAuthor.php?author_id=${authorId}`)
            .then(data => {
                books = data.books || [];
                render();
            });
    };

    loadBooks();
});
