function filterProducts() {
    const search = document.getElementById('search').value.toLowerCase();
    const category = document.getElementById('category').value.toLowerCase();
    const products = document.getElementsByClassName('product');

    Array.from(products).forEach(product => {
        const name = product.getElementsByTagName('h3')[0].innerText.toLowerCase();
        const prodCategory = product.getElementsByTagName('p')[1].innerText.toLowerCase();

        if ((name.includes(search) || search === "") && (prodCategory.includes(category) || category === "")) {
            product.style.display = 'block';
        } else {
            product.style.display = 'none';
        }
    });
}