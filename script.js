// Get form elements
const addProductBtn = document.getElementById('add-product-btn');
const addProductForm = document.getElementById('add-product-form');
const inventoryTable = document.getElementById('inventory-table');

// Show add product form when button is clicked
addProductBtn.addEventListener('click', () => {
    addProductForm.style.display = 'block';
});

// Sample function to add a new product to the inventory table
function addProduct(name, category, price, stock) {
    const row = document.createElement('tr');
    row.innerHTML = `
        <td>${name}</td>
        <td>${category}</td>
        <td>${price}</td>
        <td>${stock}</td>
        <td><button class="delete-btn">Delete</button></td>
    `;
    inventoryTable.appendChild(row);
}

// Example usage:
addProduct('Dress', 'Clothing', 50, 10);  // Add a sample product to start
