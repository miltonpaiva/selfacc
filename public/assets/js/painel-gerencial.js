// ========== EDITAR PRODUTO ==========
async function openEditProductPopup(productId) {
    const popup = document.getElementById('editProductPopup');
    if (!popup) {
        console.error('Popup de edição de produto não encontrado!');
        return;
    }

    try {
        const response = await fetch(`/admin/products/${productId}`);
        const product = await response.json();

        document.getElementById('edit_product_id').value = product.p_id;
        document.getElementById('edit_name').value = product.p_name;
        document.getElementById('edit_price').value = product.p_price;
        document.getElementById('edit_description').value = product.p_description || '';
        document.getElementById('edit_category').value = product.p_sv_category_pd_fk;
        
        // Resetar arquivo
        const editImageInput = document.getElementById('edit_image');
        if (editImageInput) {
            editImageInput.value = '';
            updateFileInputLabel(editImageInput);
        }

        // Mostrar popup
        popup.style.display = 'flex';
    } catch (error) {
        console.error('Erro ao carregar produto:', error);
        alert('Erro ao carregar produto: ' + error.message);
    }
}

// Função para atualizar label de upload de arquivo
function updateFileInputLabel(fileInput) {
    const label = fileInput.nextElementSibling;
    if (!label) return;

    if (fileInput.files.length > 0) {
        const fileName = fileInput.files[0].name;
        const fileSize = (fileInput.files[0].size / 1024 / 1024).toFixed(2);
        label.innerHTML = `
            <span class="product-popup__file-icon">✓</span>
            <span class="product-popup__file-text">${fileName}</span>
            <small>${fileSize} MB</small>
        `;
        label.style.borderColor = 'var(--color-turquoise)';
        label.style.backgroundColor = 'rgba(0, 206, 209, 0.05)';
    } else {
        label.innerHTML = `
            <span class="product-popup__file-icon">🖼️</span>
            <span class="product-popup__file-text">Clique para selecionar uma imagem</span>
            <small>PNG, JPG, JPEG (máx. 2MB)</small>
        `;
        label.style.borderColor = '';
        label.style.backgroundColor = '';
    }
}

// Adicionar event listeners para file inputs
document.addEventListener('DOMContentLoaded', function() {
    const fileInputs = document.querySelectorAll('.product-popup__file-input-field');
    fileInputs.forEach(fileInput => {
        fileInput.addEventListener('change', function() {
            updateFileInputLabel(this);
        });
    });
});

function closeEditProductPopup() {
    const popup = document.getElementById('editProductPopup');
    if (popup) {
        popup.style.display = 'none';
    }
    const imageInput = document.getElementById('edit_image');
    if (imageInput) imageInput.value = '';
}

async function updateProduct() {
    const id = document.getElementById('edit_product_id').value;
    const name = document.getElementById('edit_name').value;
    const price = document.getElementById('edit_price').value;
    const description = document.getElementById('edit_description').value;
    const category_id = document.getElementById('edit_category').value;
    const imageFile = document.getElementById('edit_image').files[0];

    if (!name || !price || !category_id) {
        alert("Nome, Preço e Categoria são obrigatórios!");
        return;
    }

    // Se houver imagem, enviar como FormData
    if (imageFile) {
        const formData = new FormData();
        formData.append('name', name);
        formData.append('price', price);
        formData.append('description', description);
        formData.append('category_id', category_id);
        formData.append('image', imageFile);
        formData.append('_method', 'PUT');

        try {
            const response = await fetch(`/admin/products/${id}`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                alert("Produto atualizado com sucesso!");
                closeEditProductPopup();
                location.reload();
            } else {
                alert("Erro ao atualizar produto: " + (result.message || ''));
            }
        } catch (error) {
            console.error('Erro:', error);
            alert("Erro ao atualizar produto: " + error.message);
        }
    } else {
        // Se não houver imagem, enviar como JSON
        const data = {
            name: name,
            price: price,
            description: description,
            category_id: category_id
        };

        try {
            const response = await fetch(`/admin/products/${id}`, {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                alert("Produto atualizado com sucesso!");
                closeEditProductPopup();
                location.reload();
            } else {
                alert("Erro ao atualizar produto: " + (result.message || ''));
            }
        } catch (error) {
            console.error('Erro:', error);
            alert("Erro ao atualizar produto: " + error.message);
        }
    }
}

// ========== NOVO PRODUTO ==========
function openNewProductPopup() {
    const popup = document.getElementById('newProductPopup');
    if (!popup) {
        console.error('Popup de novo produto não encontrado!');
        return;
    }

    document.getElementById('new_name').value = '';
    document.getElementById('new_price').value = '';
    document.getElementById('new_description').value = '';
    document.getElementById('new_image').value = '';

    // Mostrar popup
    popup.style.display = 'flex';
}

function closeNewProductPopup() {
    const popup = document.getElementById('newProductPopup');
    if (popup) {
        popup.style.display = 'none';
    }
    const imageInput = document.getElementById('new_image');
    if (imageInput) imageInput.value = '';
}

async function createProduct() {
    const name = document.getElementById('new_name').value;
    const price = document.getElementById('new_price').value;
    const description = document.getElementById('new_description').value;
    const category_id = document.getElementById('new_category').value;
    const imageFile = document.getElementById('new_image').files[0];

    if (!name || !price || !category_id) {
        alert("Nome, Preço e Categoria são obrigatórios!");
        return;
    }

    const formData = new FormData();
    formData.append('p_name', name);
    formData.append('p_price', price);
    formData.append('p_description', description);
    formData.append('p_sv_category_pd_fk', category_id);
    if (imageFile) {
        formData.append('image', imageFile);
    }

    try {
        const response = await fetch('/admin/products', {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            alert("Produto criado com sucesso!");
            closeNewProductPopup();
            location.reload();
        } else {
            alert("Erro ao criar produto: " + (result.message || ''));
        }
    } catch (error) {
        console.error('Erro:', error);
        alert("Erro ao criar produto: " + error.message);
    }
}

// ========== CATEGORIAS ==========
function openCategoriesPopup() {
    const popup = document.getElementById('categoriesPopup');
    if (!popup) {
        console.error('Popup de categorias não encontrado!');
        return;
    }

    popup.style.display = 'flex';
    loadCategories();
}

function closeCategoriesPopup() {
    const popup = document.getElementById('categoriesPopup');
    if (popup) {
        popup.style.display = 'none';
    }
}

async function loadCategories() {
    try {
        const response = await fetch('/admin/categories');
        const result = await response.json();

        if (result.success) {
            const categoriesList = document.getElementById('categoriesList');
            if (!categoriesList) return;
            
            categoriesList.innerHTML = '';

            if (result.categories.length === 0) {
                categoriesList.innerHTML = '<p style="text-align: center; color: #999; padding: 20px;">Nenhuma categoria cadastrada ainda.</p>';
                return;
            }

            result.categories.forEach(category => {
                const categoryItem = document.createElement('div');
                categoryItem.className = 'category-item';
                
                categoryItem.innerHTML = `
                    <span class="category-item__name">${category.title}</span>
                    <div class="category-item__actions">
                        <button class="category-item__btn category-item__btn--edit" onclick="openEditCategoryPopup(${category.id}, '${category.title.replace(/'/g, "\\'")}')">
                            ✎ Editar
                        </button>
                        <button class="category-item__btn category-item__btn--delete" onclick="deleteCategory(${category.id})">
                            🗑️ Deletar
                        </button>
                    </div>
                `;
                
                categoriesList.appendChild(categoryItem);
            });
        }
    } catch (error) {
        console.error('Erro ao carregar categorias:', error);
        alert("Erro ao carregar categorias: " + error.message);
    }
}

async function addNewCategory() {
    const categoryName = document.getElementById('new_category_name').value;

    if (!categoryName || categoryName.trim() === '') {
        alert("Digite um nome para a categoria!");
        return;
    }

    try {
        const response = await fetch('/admin/categories', {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ title: categoryName })
        });

        const result = await response.json();

        if (result.success) {
            alert("Categoria criada com sucesso!");
            document.getElementById('new_category_name').value = '';
            loadCategories();
        } else {
            alert("Erro ao criar categoria: " + result.message);
        }
    } catch (error) {
        console.error('Erro:', error);
        alert("Erro ao criar categoria: " + error.message);
    }
}

function openEditCategoryPopup(categoryId, categoryTitle) {
    const newTitle = prompt("Editar categoria:", categoryTitle);
    if (newTitle !== null && newTitle.trim() !== '') {
        updateCategory(categoryId, newTitle);
    }
}

async function updateCategory(categoryId, newTitle) {
    try {
        const response = await fetch(`/admin/categories/${categoryId}/update`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ title: newTitle })
        });

        const result = await response.json();

        if (result.success) {
            alert("Categoria atualizada com sucesso!");
            loadCategories();
        } else {
            alert("Erro ao atualizar categoria: " + result.message);
        }
    } catch (error) {
        console.error('Erro:', error);
        alert("Erro ao atualizar categoria: " + error.message);
    }
}

async function deleteCategory(categoryId) {
    if (!confirm("Tem certeza que deseja deletar esta categoria?")) {
        return;
    }

    try {
        const response = await fetch(`/admin/categories/${categoryId}/delete`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const result = await response.json();

        if (result.success) {
            alert("Categoria deletada com sucesso!");
            loadCategories();
        } else {
            alert("Erro ao deletar categoria: " + result.message);
        }
    } catch (error) {
        console.error('Erro:', error);
        alert("Erro ao deletar categoria: " + error.message);
    }
}
