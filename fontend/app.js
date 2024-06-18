document.addEventListener('DOMContentLoaded', () => {
    const bookList = document.getElementById('book-list');
    const createBookForm = document.getElementById('create-book-form');

    function listBooks() {
        fetch('api/books/index.php')
            .then(response => response.json())
            .then(data => {
                bookList.innerHTML = '';
                if (data.records) {
                    data.records.forEach(book => {
                        const bookItem = document.createElement('div');
                        bookItem.className = 'book-item';
                        bookItem.innerHTML = `
                            <h3>${book.title}</h3>
                            <p>Auteur: ${book.author}</p>
                            <p>Description: ${book.description}</p>
                            <button onclick="showBook(${book.id})">Afficher</button>
                            <button onclick="editBook(${book.id})">Modifier</button>
                            <button onclick="deleteBook(${book.id})">Supprimer</button>
                        `;
                        bookList.appendChild(bookItem);
                    });
                } else {
                    bookList.innerHTML = '<p>Aucun livre trouvé.</p>';
                }
            })
            .catch(error => console.error('Error fetching books:', error));
    }

    createBookForm.addEventListener('submit', (event) => {
        event.preventDefault();

        const formData = new FormData(createBookForm);
        const data = {
            title: formData.get('title'),
            author: formData.get('author'),
            description: formData.get('description')
        };

        fetch('api/books/create.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            listBooks();
            createBookForm.reset();
        })
        .catch(error => console.error('Error adding book:', error));
    });

    window.showBook = (id) => {
        fetch(`api/books/show.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                alert(`Titre: ${data.title}\nAuteur: ${data.author}\nDescription: ${data.description}`);
            })
            .catch(error => console.error('Error fetching book details:', error));
    };

    window.deleteBook = (id) => {
        if (confirm('Voulez-vous vraiment supprimer ce livre ?')) {
            fetch(`api/books/delete.php?id=${id}`, {
                method: 'DELETE'
            })
            .then(response => response.json())
            .then(data => {
                listBooks();
            })
            .catch(error => console.error('Error deleting book:', error));
        }
    };

    window.editBook = (id) => {
        alert('La fonction de modification n\'est pas encore implémentée.');
    };

    listBooks();
});
