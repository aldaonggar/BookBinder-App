document.addEventListener('DOMContentLoaded', function() {
    const starIcon = document.getElementById('favoriteIcon');
    const bookDescElement = document.getElementById("bookDescription");

    const isbn = bookDescElement.getAttribute('data-isbn');
    const bookId = starIcon.getAttribute('data-book-id');
    const url = `https://www.googleapis.com/books/v1/volumes?q=isbn:${isbn}`;

    // Fetch book description
    fetch(url)
        .then(response => response.json())
        .then(data => {
            const description = data.items[0].volumeInfo.description;
            bookDescElement.innerHTML = description;
        })
        .catch(error => {
            console.log('Error:', error);
        });

    // Check if the book is favorited when the page loads
    fetch(`/get-favorite-status/${bookId}`)
        .then(response => response.json())
        .then(data => {
            console.log('favorite', data.isFavorite)
            if (data.isFavorite) {
                starIcon.classList.remove('far');
                starIcon.classList.add('fas');
            } else {
                starIcon.classList.remove('fas');
                starIcon.classList.add('far');
            }
        })
        .catch(error => console.error('Error:', error));

    // Handle star icon click
    starIcon.addEventListener('click', function() {
        fetch(`/toggle-favorite/${bookId}`, {
            method: 'POST',
        })
            .then(response => response.json())
            .then(data => {
                console.log('favorite', data.isFavorite)
                if (data.isFavorite) {
                    starIcon.classList.remove('far');
                    starIcon.classList.add('fas');
                } else {
                    starIcon.classList.remove('fas');
                    starIcon.classList.add('far');
                }
            })
            .catch(error => console.error('Error:', error));
    });
});
