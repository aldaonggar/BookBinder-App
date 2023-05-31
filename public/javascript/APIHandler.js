document.addEventListener('DOMContentLoaded', function() {
    const bookDescELement = document.getElementById("bookDescription");
    const isbn = bookDescELement.getAttribute('data-isbn');
    const url = `https://www.googleapis.com/books/v1/volumes?q=isbn:${isbn}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            const description = data.items[0].volumeInfo.description;
            document.getElementById('bookDescription').innerHTML = description;
        })
        .catch(error => {
            console.log('Error:', error);
        });
});